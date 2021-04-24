<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use League\Csv\Reader;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class to handle the console command to import stock from the input.
 *
 * @author Adarsh Manickam <adarsh.apple@icloud.com>
 */
class ImportStock extends Command
{
    /**
     * Command description
     */
    private const COMMAND_DESCRIPTION = "Imports stock from a given CSV file.";

    /**
     * File argument name
     */
    private const ARGUMENT_FILE_NAME = "file";

    /**
     * File argument description
     */
    private const ARGUMENT_FILE_DESCRIPTION = "File to import";

    /**
     * CSV header offset
     */
    private const CSV_HEADER_OFFSET = 0;


    private const COLUMN_SKU = "SKU";


    private const COLUMN_BRANCH = "BRANCH";


    private const COLUMN_STOCK = "STOCK";


    private const INSERT_BATCH_SIZE = 20;

    /**
     * @var string Console command name
     */
    protected static $defaultName = "stock:import";

    /**
     * @var Registry
     */
    private Registry $doctrine;

    /**
     * ImportStock constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        parent::__construct();
        $this->doctrine = $container->get("doctrine");
    }

    /**
     * Configures the command.
     */
    protected function configure(): void
    {
        $this
            ->setDescription(self::COMMAND_DESCRIPTION)
            ->addArgument(
                self::ARGUMENT_FILE_NAME,
                InputArgument::REQUIRED,
                self::ARGUMENT_FILE_DESCRIPTION
            );
    }

    /**
     * Imports stock and return 0 if successful.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument(self::ARGUMENT_FILE_NAME);

        try {
            $records = Reader::createFromPath($filePath)->setHeaderOffset(self::CSV_HEADER_OFFSET)->getRecords();
            $progressBar = new ProgressBar($output);
            $failedRows = [];

            foreach ($records as $offset => $record) {
                $stock = new Stock(
                    $record[self::COLUMN_SKU],
                    $record[self::COLUMN_BRANCH],
                    (float) $record[self::COLUMN_STOCK]
                );

                try {
                    $this->doctrine->getManager()->persist($stock);
                    $this->doctrine->getManager()->flush();
                } catch (UniqueConstraintViolationException $exception) {
                    // TODO: Update when entity is already present.
                    $failedRows[] = $offset;
                    $this->doctrine->resetManager();
                }

                $progressBar->advance();
            }

            $this->doctrine->getManager()->clear();
            $this->doctrine->getManager()->flush();
            $progressBar->finish();
            $count = $offset - sizeof($failedRows);

            if ($count > 0) {
                $output->writeln("\nSuccessfully imported $count rows.");
            }

            if ($count === 0) {
                $output->writeln("\nCould not import any row as they all exist already.");
            } else if (sizeof($failedRows) > 0) {
                $rows = implode(", ", $failedRows);
                $output->writeln("Could not import the following rows as they exist already: $rows.");
            }
        } catch (Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
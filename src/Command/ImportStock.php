<?php declare(strict_types=1);

namespace App\Command;

use Exception;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
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

    /**
     * @var string Console command name
     */
    protected static $defaultName = "stock:import";

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
            foreach ($records as $record) {
                $output->writeln(json_encode($record));
            }
        } catch (Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
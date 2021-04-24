<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20)
     */
    private string $sku;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    private string $branch;

    /**
     * @ORM\Column(type="float")
     */
    private float $stock;

    /**
     * Stock constructor.
     *
     * @param string|null $sku
     * @param string|null $branch
     * @param float|null $stock
     */
    public function __construct(string $sku = null, string $branch = null, float $stock = null)
    {
        if ($sku) {
            $this->sku = $sku;
        }
        if ($branch) {
            $this->branch = $branch;
        }
        if ($stock) {
            $this->stock = $stock;
        }
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getStock(): float
    {
        return $this->stock;
    }

    public function setStock(float $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}

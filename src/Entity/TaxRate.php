<?php

namespace App\Entity;

use App\Repository\TaxRateRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaxRateRepository::class)
 * @ORM\Table(name="tax_rates"),
 * @ORM\HasLifecycleCallbacks()
 */
class TaxRate
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message = "Tax rate cannot be negative")
     * @Assert\NotBlank
     */
    private ?float $tax_rate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max = 255, maxMessage = "Description cannot be longer than {{ limit }} characters")
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=SaleItemCategory::class, mappedBy="tax_rate")
     */
    private $saleItemCategories;

    public function __construct()
    {
        $this->saleItemCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxRate(): ?float
    {
        return $this->tax_rate;
    }

    public function setTaxRate(float $tax_rate): self
    {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|SaleItemCategory[]
     */
    public function getSaleItemCategories(): Collection
    {
        return $this->saleItemCategories;
    }

    public function addSaleItemCategory(SaleItemCategory $saleItemCategory): self
    {
        if (!$this->saleItemCategories->contains($saleItemCategory)) {
            $this->saleItemCategories[] = $saleItemCategory;
            $saleItemCategory->setTaxRate($this);
        }

        return $this;
    }

    public function removeSaleItemCategory(SaleItemCategory $saleItemCategory): self
    {
        if ($this->saleItemCategories->removeElement($saleItemCategory)) {
            // set the owning side to null (unless already changed)
            if ($saleItemCategory->getTaxRate() === $this) {
                $saleItemCategory->setTaxRate(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        $created_at = $this->created_at->format('Y-m-d H:i:s');
        $updated_at = isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null;

        return [
            'id' => $this->getId(),
            'tax_rate' => $this->getTaxRate(),
            'description' => $this->getDescription(),
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}

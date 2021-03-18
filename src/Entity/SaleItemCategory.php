<?php

namespace App\Entity;

use App\Repository\SaleItemCategoryRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SaleItemCategoryRepository::class)
 * @ORM\Table(name="sale_item_categories", uniqueConstraints={@ORM\UniqueConstraint(name="type_unique",columns={"type"})},
 *     indexes={
 *          @ORM\Index(name="type_idx", columns={"type"}),
 *          @ORM\Index(name="description_idx", columns={"description"}),
 *          @ORM\Index(name="tax_rate_idx", columns={"tax_rate"})
 *     }),
 * @UniqueEntity(fields="type", errorPath="type", message="Sorry, this sale item category type is already in use.")
 * @ORM\HasLifecycleCallbacks()
 */
class SaleItemCategory
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(max = 50, maxMessage = "Type cannot be longer than {{ limit }} characters")
     * @Assert\NotBlank
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max = 255, maxMessage = "Description cannot be longer than {{ limit }} characters")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=TaxRate::class, inversedBy="saleItemCategories")
     * @ORM\JoinColumn(name="tax_rate", nullable=false)
     * @Assert\NotBlank
     */
    private $tax_rate;

    /**
     * @ORM\OneToMany(targetEntity=SaleItem::class, mappedBy="category")
     */
    private $saleItems;

    public function __construct()
    {
        $this->saleItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTaxRate(): ?TaxRate
    {
        return $this->tax_rate;
    }

    public function setTaxRate(?TaxRate $tax_rate): self
    {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    /**
     * @return Collection|SaleItem[]
     */
    public function getSaleItems(): Collection
    {
        return $this->saleItems;
    }

    public function addSaleItem(SaleItem $saleItem): self
    {
        if (!$this->saleItems->contains($saleItem)) {
            $this->saleItems[] = $saleItem;
            $saleItem->setCategory($this);
        }

        return $this;
    }

    public function removeSaleItem(SaleItem $saleItem): self
    {
        if ($this->saleItems->removeElement($saleItem)) {
            // set the owning side to null (unless already changed)
            if ($saleItem->getCategory() === $this) {
                $saleItem->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getDescription();
    }
}

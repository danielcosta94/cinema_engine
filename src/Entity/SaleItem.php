<?php

namespace App\Entity;

use App\Repository\SaleItemRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SaleItemRepository::class)
 * @ORM\Table(name="sale_items", uniqueConstraints={@ORM\UniqueConstraint(name="barcode_unique",columns={"barcode"})},
 *     indexes={
 *          @ORM\Index(name="barcode_idx", columns={"barcode"}),
 *          @ORM\Index(name="title_idx", columns={"title"}),
 *          @ORM\Index(name="price_idx", columns={"price"}),
 *          @ORM\Index(name="category_idx", columns={"category"})
 *     }),
 * @UniqueEntity(fields="barcode", errorPath="barcode", message="Sorry, this barcode is already in use.")
 * @ORM\HasLifecycleCallbacks()
 */
class SaleItem implements \JsonSerializable
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max = 255, maxMessage = "Barcode cannot be longer than {{ limit }} characters")
     * @Assert\NotBlank
     */
    private $barcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max = 255, maxMessage = "Title cannot be longer than {{ limit }} characters")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Assert\Length(max = 255, maxMessage = "Description be longer than {{ limit }} characters")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message = "Price cannot be negative")
     * @Assert\NotBlank
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=SaleItemCategory::class, inversedBy="saleItems")
     * @ORM\JoinColumn(name="category", nullable=false)
     * @Assert\NotBlank
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=ShoppingCartItem::class, mappedBy="sale_item", orphanRemoval=true)
     */
    private $shoppingCartItems;

    public function __construct()
    {
        $this->shoppingCartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?SaleItemCategory
    {
        return $this->category;
    }

    public function setCategory(?SaleItemCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|ShoppingCartItem[]
     */
    public function getShoppingCartItems(): Collection
    {
        return $this->shoppingCartItems;
    }

    public function addShoppingCartItem(ShoppingCartItem $shoppingCartItem): self
    {
        if (!$this->shoppingCartItems->contains($shoppingCartItem)) {
            $this->shoppingCartItems[] = $shoppingCartItem;
            $shoppingCartItem->setSaleItem($this);
        }

        return $this;
    }

    public function removeShoppingCartItem(ShoppingCartItem $shoppingCartItem): self
    {
        if ($this->shoppingCartItems->removeElement($shoppingCartItem)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartItem->getSaleItem() === $this) {
                $shoppingCartItem->setSaleItem(null);
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
            'barcode' => $this->getBarcode(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'category' => $this->getCategory()->getDescription(),
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}

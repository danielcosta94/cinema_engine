<?php

namespace App\Entity;

use App\Repository\ShoppingCartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ShoppingCartItemRepository::class)
 * @ORM\Table(name="shopping_cart_items", uniqueConstraints={@ORM\UniqueConstraint(name="shopping_cart_item_unique",columns={"shopping_cart", "sale_item"})},
 *     indexes={
 *          @ORM\Index(name="shopping_cart_idx", columns={"shopping_cart"}),
 *          @ORM\Index(name="sale_item_idx", columns={"sale_item"})
 *     }),
 * @UniqueEntity(fields={"shopping_cart", "sale_item"}, message="Sorry, this sale time was already added to this shopping cart.")
 * @ORM\HasLifecycleCallbacks()
 */
class ShoppingCartItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ShoppingCart::class, inversedBy="shoppingCartItems")
     * @ORM\JoinColumn(name="shopping_cart", nullable=false)
     * @Assert\NotBlank
     */
    private $shopping_cart;

    /**
     * @ORM\ManyToOne(targetEntity=SaleItem::class, inversedBy="shoppingCartItems")
     * @ORM\JoinColumn(name="sale_item", nullable=false)
     * @Assert\NotBlank
     */
    private $sale_item;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message = "Quantity must be a positive integer value")
     * @Assert\NotBlank
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message = "Price net cannot be negative")
     * @Assert\NotBlank
     */
    private $price_net_unit;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message = "Tax rate cannot be negative")
     * @Assert\NotBlank
     */
    private $tax_rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShoppingCartId(): ?ShoppingCart
    {
        return $this->shopping_cart;
    }

    public function setShoppingCartId(?ShoppingCart $shopping_cart): self
    {
        $this->shopping_cart = $shopping_cart;

        return $this;
    }

    public function getSaleItem(): ?SaleItem
    {
        return $this->sale_item;
    }

    public function setSaleItem(?SaleItem $sale_item): self
    {
        $this->sale_item = $sale_item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPriceNetUnit(): ?float
    {
        return $this->price_net_unit;
    }

    public function setPriceNetUnit(float $price_net_unit): self
    {
        $this->price_net_unit = $price_net_unit;

        return $this;
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
}

<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShoppingCartRepository::class)
 * @ORM\Table(name="shopping_carts"),
 * @ORM\HasLifecycleCallbacks()
 */
class ShoppingCart
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Voucher::class, mappedBy="shopping_cart")
     */
    private $vouchers;

    /**
     * @ORM\OneToMany(targetEntity=ShoppingCartItem::class, mappedBy="shopping_cart", orphanRemoval=true)
     */
    private $shoppingCartItems;

    public function __construct()
    {
        $this->vouchers = new ArrayCollection();
        $this->shoppingCartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseAt(): ?\DateTimeInterface
    {
        return $this->purchase_at;
    }

    public function setPurchaseAt(\DateTimeInterface $purchase_at): self
    {
        $this->purchase_at = $purchase_at;

        return $this;
    }

    /**
     * @return Collection|Voucher[]
     */
    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher(Voucher $voucher): self
    {
        if (!$this->vouchers->contains($voucher)) {
            $this->vouchers[] = $voucher;
            $voucher->setShoppingCart($this);
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): self
    {
        if ($this->vouchers->removeElement($voucher)) {
            // set the owning side to null (unless already changed)
            if ($voucher->getShoppingCart() === $this) {
                $voucher->setShoppingCart(null);
            }
        }

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
            $shoppingCartItem->setShoppingCartId($this);
        }

        return $this;
    }

    public function removeShoppingCartItem(ShoppingCartItem $shoppingCartItem): self
    {
        if ($this->shoppingCartItems->removeElement($shoppingCartItem)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartItem->getShoppingCartId() === $this) {
                $shoppingCartItem->setShoppingCartId(null);
            }
        }

        return $this;
    }
}

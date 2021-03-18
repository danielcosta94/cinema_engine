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
     * @ORM\Column(type="datetime")
     */
    private $purchase_at;

    /**
     * @ORM\ManyToOne(targetEntity=Voucher::class, inversedBy="shoppingCarts")
     */
    private $voucher;

    /**
     * @ORM\OneToMany(targetEntity=ShoppingCartItem::class, mappedBy="shopping_cart", orphanRemoval=true)
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

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }
}

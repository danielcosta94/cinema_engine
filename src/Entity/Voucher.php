<?php

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 * @ORM\Table(name="vouchers", uniqueConstraints={@ORM\UniqueConstraint(name="code_unique",columns={"code"})},
 *     indexes={
 *          @ORM\Index(name="code_idx", columns={"code"}),
 *     }),
 * @UniqueEntity(fields="code", errorPath="code", message="Sorry, this code is already in use.")
 * @ORM\HasLifecycleCallbacks()
 */
class Voucher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max = 255, maxMessage = "Code be longer than {{ limit }} characters")
     * @Assert\NotBlank
     */
    private $code;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Assert\Length(max = 255, maxMessage = "Description be longer than {{ limit }} characters")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(min = 0, max = 100, notInRangeMessage = "Percentage must be between {{ min }} and {{ max }}")
     */
    private $discount_percentage;

    /**
     * @ORM\OneToMany(targetEntity=ShoppingCart::class, mappedBy="voucher")
     */
    private $shoppingCarts;

    public function __construct()
    {
        $this->shoppingCarts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
     * @return mixed
     */
    public function getDiscountPercentage(): ?float
    {
        return $this->discount_percentage;
    }

    /**
     * @param mixed $discount_percentage
     * @return Voucher
     */
    public function setDiscountPercentage($discount_percentage): self
    {
        $this->discount_percentage = $discount_percentage;

        return $this;
    }

    /**
     * @return Collection|ShoppingCart[]
     */
    public function getShoppingCarts(): Collection
    {
        return $this->shoppingCarts;
    }

    public function addShoppingCart(ShoppingCart $shoppingCart): self
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
            $shoppingCart->setVoucher($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->removeElement($shoppingCart)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCart->getVoucher() === $this) {
                $shoppingCart->setVoucher(null);
            }
        }

        return $this;
    }
}

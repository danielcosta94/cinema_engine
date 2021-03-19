<?php

namespace App\Service;

use App\Entity\SaleItemCategory;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use App\Entity\Voucher;
use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ShoppingCartService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ShoppingCartRepository
     */
    private ShoppingCartRepository $shoppingCartRepository;

    /**
     * @var SaleItemCategoryService
     */
    private SaleItemCategoryService $saleItemCategoryService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ShoppingCartRepository $shoppingCartRepository,
        SaleItemCategoryService $saleItemCategoryService
    ) {
        $this->entityManager = $entityManager;
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->saleItemCategoryService = $saleItemCategoryService;
    }

    public function saveShoppingCart(ShoppingCart $shoppingCart): ShoppingCart
    {
        $this->entityManager->persist($shoppingCart);
        $this->entityManager->flush();

        return $shoppingCart;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function findShoppingCarts(array $data): array
    {
        try {
            if (!empty($data['limit'])) {
                $limit = $data['limit'];
                unset($data['limit']);
            } else {
                $limit = null;
            }

            if (!empty($data['offset'])) {
                $offset = $data['offset'];
                unset($data['offset']);
            } else {
                $offset = null;
            }

            if (!empty($data['order'])) {
                $orderBy = [];
                foreach (explode(',', $data['order']) as $orderByItem) {
                    $orderByItemSplit = explode('=', $orderByItem);
                    $orderByItemSplit[1] ??= Criteria::ASC;
                    if (count($orderByItemSplit)) {
                        $orderBy[$orderByItemSplit[0]] = $orderByItemSplit[1];
                    }
                }
                unset($data['order']);
            } else {
                $orderBy = null;
            }

            return $this->shoppingCartRepository->findBy($data, $orderBy, $limit, $offset);
        } catch (ORMException $ORMException) {
            throw $ORMException;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findShoppingCartById($id, $lockMode = null, $lockVersion = null): ?ShoppingCart
    {
        try {
            return $this->shoppingCartRepository->find($id, $lockMode, $lockVersion);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findShoppingCartByCriteria(array $criteria = [], array $orderBy = null): ?ShoppingCart
    {
        try {
            return $this->shoppingCartRepository->findOneBy($criteria, $orderBy);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function deleteShoppingCart($id): ?ShoppingCart
    {
        try {
            if ($shoppingCart = $this->findShoppingCartById($id)) {
                $this->entityManager->remove($shoppingCart);
                $this->entityManager->flush();
            }
            return $shoppingCart;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function addVoucherToShoppingCart(ShoppingCart $shoppingCart, Voucher $voucher): bool
    {
        $shoppingCartVoucher = $shoppingCart->getVoucher();
        if ($shoppingCartVoucher === null) {
            $shoppingCart->setVoucher($voucher);
            $this->saveShoppingCart($shoppingCart);
            return $shoppingCart->getVoucher() !== null;
        } else {
            throw new UnprocessableEntityHttpException("Shopping cart has already one voucher code associated");
        }
    }

    private function getTaxesPriceOfShoppingCartItem(ShoppingCartItem $shoppingCartItem): float
    {
        return ($shoppingCartItem->getPriceNetUnit() * $shoppingCartItem->getQuantity()) * ($shoppingCartItem->getTaxRate() / 100);
    }

    private function getNetPriceOfShoppingCartItem(ShoppingCartItem $shoppingCartItem): float
    {
        return $shoppingCartItem->getPriceNetUnit() * $shoppingCartItem->getQuantity();
    }

    private function getGrossPriceOfShoppingCartItem(ShoppingCartItem $shoppingCartItem): float
    {
        return $this->getNetPriceOfShoppingCartItem($shoppingCartItem) * (($shoppingCartItem->getTaxRate() / 100) + 1);
    }

    private function getDiscountValueOfPrice(float $price, float $discountPercentage): float
    {
        return ($discountPercentage / 100) * $price;
    }

    public function getGrossTotalPriceOfShoppingCart(ShoppingCart $shoppingCart): float
    {
        $total = 0;
        $voucher = $shoppingCart->getVoucher();

        if ($voucher != null) {
            $discount_percentage = $voucher->getDiscountPercentage();

            foreach ($shoppingCart->getShoppingCartItems() as $shoppingCartItem) {
                $shoppingCartItemTotalGrossPrice = $this->getGrossPriceOfShoppingCartItem($shoppingCartItem);
                $total += $shoppingCartItemTotalGrossPrice;
                if ($shoppingCartItem->getSaleItem()->getCategory()->getType() === SaleItemCategory::TICKET_TYPE) {
                    $total -= $this->getDiscountValueOfPrice($shoppingCartItemTotalGrossPrice, $discount_percentage);
                }
            }
        } else {
            foreach ($shoppingCart->getShoppingCartItems() as $shoppingCartItem) {
                $total += $this->getGrossPriceOfShoppingCartItem($shoppingCartItem);
            }
        }

        return round($total, 2);
    }

    private function roundShoppingCartInvoicePrices(array &$data)
    {
        $data['total_net_price'] = round($data['total_net_price'], 2);
        $data['total_gross_price'] = round($data['total_gross_price'], 2);
        foreach ($data['total_taxes_paid_by_category'] as $key => $total_tax_paid_by_category) {
            $data['total_taxes_paid_by_category'][$key] = round($total_tax_paid_by_category, 2);
        }
    }

    public function getShoppingCartInvoiceDetails(ShoppingCart $shoppingCart): array
    {
        $data = [
            'shopping_cart_id' => $shoppingCart->getId(),
            'purchase_at' => $shoppingCart->getPurchaseAt()->format('Y-m-d H:i:s'),
            'sales_items' => [],
            'total_taxes_paid_by_category' => [],
            'total_taxes' => 0,
            'total_net_price' => 0,
            'total_gross_price' => 0,
        ];

        try {
            $saleItemCategories = $this->saleItemCategoryService->findSaleItemCategories(['order' => ['type' => Criteria::ASC]]);
            foreach ($saleItemCategories as $saleItemCategory) {
                $data['total_taxes_paid_by_category'][$saleItemCategory->getType()] = 0;
            }

            $voucher = $shoppingCart->getVoucher();

            if ($voucher != null) {
                $discount_percentage = $voucher->getDiscountPercentage();

                foreach ($shoppingCart->getShoppingCartItems() as $shoppingCartItem) {
                    $saleItem = $shoppingCartItem->getSaleItem();
                    $saleItemCategory = $saleItem->getCategory()->getType();
                    $shoppingCartItemTotalGrossPrice = $this->getGrossPriceOfShoppingCartItem($shoppingCartItem);
                    $saleItemTaxesPrice = $this->getTaxesPriceOfShoppingCartItem($shoppingCartItem);

                    if ($saleItemCategory === SaleItemCategory::TICKET_TYPE) {
                        $shoppingCartItemTotalGrossPrice -= $this->getDiscountValueOfPrice($this->getGrossPriceOfShoppingCartItem($shoppingCartItem), $discount_percentage);
                        $saleItemTaxesPrice -= $this->getDiscountValueOfPrice($this->getGrossPriceOfShoppingCartItem($shoppingCartItem), $discount_percentage);
                    }

                    $data['sales_items'][] = [
                        'title' => $saleItem->getTitle(),
                        'net_price_unit' => $shoppingCartItem->getPriceNetUnit(),
                        'quantity' => $shoppingCartItem->getQuantity(),
                        'discount' => $saleItemCategory === SaleItemCategory::TICKET_TYPE ? $discount_percentage : 0,
                        'tax_rate' => $shoppingCartItem->getTaxRate(),
                        'total_gross_price' => $shoppingCartItemTotalGrossPrice,
                    ];
                    $data['total_taxes_paid_by_category'][$saleItemCategory] += $saleItemTaxesPrice;
                    $data['total_taxes'] += $saleItemTaxesPrice;
                    $data['total_net_price'] += $this->getNetPriceOfShoppingCartItem($shoppingCartItem);
                    $data['total_gross_price'] += $shoppingCartItemTotalGrossPrice;
                }
            } else {
                foreach ($shoppingCart->getShoppingCartItems() as $shoppingCartItem) {
                    $saleItem = $shoppingCartItem->getSaleItem();
                    $saleItemCategory = $saleItem->getCategory()->getType();
                    $shoppingCartItemTotalGrossPrice = $this->getGrossPriceOfShoppingCartItem($shoppingCartItem);

                    $data['sales_items'][] = [
                        'title' => $saleItem->getTitle(),
                        'net_price_unit' => $shoppingCartItem->getPriceNetUnit(),
                        'quantity' => $shoppingCartItem->getQuantity(),
                        'discount' => 0,
                        'tax_rate' => $shoppingCartItem->getTaxRate(),
                        'total_gross_price' => $shoppingCartItemTotalGrossPrice,
                    ];
                    $data['total_taxes_paid_by_category'][$saleItemCategory] += $this->getTaxesPriceOfShoppingCartItem($shoppingCartItem);
                    $data['total_taxes'] += $this->getTaxesPriceOfShoppingCartItem($shoppingCartItem);
                    $data['total_net_price'] += $this->getNetPriceOfShoppingCartItem($shoppingCartItem);
                    $data['total_gross_price'] += $shoppingCartItemTotalGrossPrice;
                }
            }

            $this->roundShoppingCartInvoicePrices($data);
            return $data;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
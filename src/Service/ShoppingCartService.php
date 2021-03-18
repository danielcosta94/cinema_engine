<?php

namespace App\Service;

use App\Entity\ShoppingCart;
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

    public function __construct(EntityManagerInterface $entityManager, ShoppingCartRepository $shoppingCartRepository)
    {
        $this->entityManager = $entityManager;
        $this->shoppingCartRepository = $shoppingCartRepository;
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
}
<?php

namespace App\Service;

use App\Entity\SaleItem;
use App\Repository\SaleItemRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;

class SaleItemService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var SaleItemRepository
     */
    private SaleItemRepository $saleItemRepository;

    public function __construct(EntityManagerInterface $entityManager, SaleItemRepository $saleItemRepository)
    {
        $this->entityManager = $entityManager;
        $this->saleItemRepository = $saleItemRepository;
    }

    public function saveSaleItem(SaleItem $saleItem): SaleItem
    {
        return $this->persistSaleItem($saleItem);
    }

    private function persistSaleItem(SaleItem $saleItem): SaleItem
    {
        $this->entityManager->persist($saleItem);
        $this->entityManager->flush();

        return $saleItem;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function findSaleItems(array $data): array
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

            return $this->saleItemRepository->findBy($data, $orderBy, $limit, $offset);
        } catch (ORMException $ORMException) {
            throw $ORMException;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findSaleItem($id, $lockMode = null, $lockVersion = null): ?SaleItem
    {
        try {
            return $this->saleItemRepository->find($id, $lockMode, $lockVersion);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function deleteSaleItem($id): ?SaleItem
    {
        try {
            if ($saleItem = $this->findSaleItem($id)) {
                $this->entityManager->remove($saleItem);
                $this->entityManager->flush();
            }
            return $saleItem;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
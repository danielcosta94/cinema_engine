<?php

namespace App\Service;

use App\Entity\SaleItemCategory;
use App\Repository\SaleItemCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;

class SaleItemCategoryService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var SaleItemCategoryRepository
     */
    private SaleItemCategoryRepository $saleItemCategoryRepository;

    public function __construct(EntityManagerInterface $entityManager, SaleItemCategoryRepository $saleItemCategoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->saleItemCategoryRepository = $saleItemCategoryRepository;
    }

    public function saveSaleItemCategory(SaleItemCategory $saleItemCategory): SaleItemCategory
    {
        $this->entityManager->persist($saleItemCategory);
        $this->entityManager->flush();

        return $saleItemCategory;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function findSaleItemCategories(array $data): array
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
                $orderBy = $data['order'];
                unset($data['order']);
            } else {
                $orderBy = null;
            }

            return $this->saleItemCategoryRepository->findBy($data, $orderBy, $limit, $offset);
        } catch (ORMException $ORMException) {
            throw $ORMException;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findSaleItemCategoryById($id, $lockMode = null, $lockVersion = null): ?SaleItemCategory
    {
        try {
            return $this->saleItemCategoryRepository->find($id, $lockMode, $lockVersion);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findSaleItemCategoryByCriteria(array $criteria = [], array $orderBy = null): ?SaleItemCategory
    {
        try {
            return $this->saleItemCategoryRepository->findOneBy($criteria, $orderBy);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function deleteSaleItemCategory($id): ?SaleItemCategory
    {
        try {
            if ($saleItemCategory = $this->findSaleItemCategoryById($id)) {
                $this->entityManager->remove($saleItemCategory);
                $this->entityManager->flush();
            }
            return $saleItemCategory;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
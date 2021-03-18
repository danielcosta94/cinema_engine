<?php

namespace App\Service;

use App\Entity\Voucher;
use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;

class VoucherService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var VoucherRepository 
     */
    private VoucherRepository $voucherRepository;

    public function __construct(EntityManagerInterface $entityManager, VoucherRepository $voucherRepository)
    {
        $this->entityManager = $entityManager;
        $this->voucherRepository = $voucherRepository;
    }

    public function saveVoucher(Voucher $voucher): Voucher
    {
        $this->entityManager->persist($voucher);
        $this->entityManager->flush();

        return $voucher;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function findVouchers(array $data): array
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

            return $this->voucherRepository->findBy($data, $orderBy, $limit, $offset);
        } catch (ORMException $ORMException) {
            throw $ORMException;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findVoucherById($id, $lockMode = null, $lockVersion = null): ?Voucher
    {
        try {
            return $this->voucherRepository->find($id, $lockMode, $lockVersion);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findVoucherByCriteria(array $criteria = [], array $orderBy = null): ?Voucher
    {
        try {
            return $this->voucherRepository->findOneBy($criteria, $orderBy);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function deleteVoucher($id): ?Voucher
    {
        try {
            if ($voucher = $this->findVoucherById($id)) {
                $this->entityManager->remove($voucher);
                $this->entityManager->flush();
            }
            return $voucher;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
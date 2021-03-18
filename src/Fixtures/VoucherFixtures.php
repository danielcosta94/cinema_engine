<?php

namespace App\Fixtures;

use App\Entity\Voucher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VoucherFixtures extends Fixture
{
    public const VOUCHER_1 = '38123812039';
    public const VOUCHER_2 = '31283120398';

    public const REFERENCE_PREFIX = 'voucher-';

    public function load(ObjectManager $manager)
    {
        /* -------- Voucher 1 --------- */
        $voucher1 = new Voucher();
        $voucher1->setCode(self::VOUCHER_1);
        $voucher1->setDescription('Ticket');
        $voucher1->setDiscountPercentage(10);
        $this->addReference(sprintf("%s%s", self::REFERENCE_PREFIX, self::VOUCHER_1), $voucher1);

        $manager->persist($voucher1);
        /* -------- End Voucher 1 --------- */


        /* -------- Voucher 2 --------- */
        $voucher2 = new Voucher();
        $voucher2->setCode(self::VOUCHER_2);
        $voucher2->setDescription('Ticket');
        $voucher2->setDiscountPercentage(20);
        $this->addReference(sprintf("%s%s", self::REFERENCE_PREFIX, self::VOUCHER_2), $voucher2);

        $manager->persist($voucher2);
        /* -------- End Voucher 2 --------- */

        $manager->flush();
    }
}

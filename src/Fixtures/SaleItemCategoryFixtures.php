<?php

namespace App\Fixtures;

use App\Entity\SaleItemCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SaleItemCategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const TICKET_TYPE = 'ticket';
    public const REFRESHMENTS_TYPE = 'refreshments';
    public const MERCHANDISE_TYPE = 'merchandise';

    public const REFERENCE_PREFIX = 'sale-item-category-';

    public function load(ObjectManager $manager)
    {
        /* -------- Sale Item Category 1 --------- */
        $saleItemCategory1 = new SaleItemCategory();
        $saleItemCategory1->setType(self::TICKET_TYPE);
        $saleItemCategory1->setDescription('Ticket');
        $saleItemCategory1->setTaxRate($this->getReference(sprintf("%s%d", TaxRateFixtures::REFERENCE_PREFIX, 1)));
        $this->addReference(sprintf("%s%s", self::REFERENCE_PREFIX, self::TICKET_TYPE), $saleItemCategory1);

        $manager->persist($saleItemCategory1);
        /* -------- End Sale Item Category 1 --------- */


        /* -------- Sale Item Category 2 --------- */
        $saleItemCategory2 = new SaleItemCategory();
        $saleItemCategory2->setType(self::REFRESHMENTS_TYPE);
        $saleItemCategory2->setDescription('Refreshments');
        $saleItemCategory2->setTaxRate($this->getReference(sprintf("%s%d", TaxRateFixtures::REFERENCE_PREFIX, 2)));
        $this->addReference(sprintf("%s%s", self::REFERENCE_PREFIX, self::REFRESHMENTS_TYPE), $saleItemCategory2);

        $manager->persist($saleItemCategory2);
        /* -------- End Sale Item Category 2 --------- */


        /* -------- Sale Item Category 3 --------- */
        $saleItemCategory3 = new SaleItemCategory();
        $saleItemCategory3->setType(self::MERCHANDISE_TYPE);
        $saleItemCategory3->setDescription('Merchandise');
        $saleItemCategory3->setTaxRate($this->getReference(sprintf("%s%d", TaxRateFixtures::REFERENCE_PREFIX, 3)));
        $this->addReference(sprintf("%s%s", self::REFERENCE_PREFIX, self::MERCHANDISE_TYPE), $saleItemCategory3);

        $manager->persist($saleItemCategory3);
        /* -------- End Sale Item Category 2 --------- */

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TaxRateFixtures::class
        ];
    }
}

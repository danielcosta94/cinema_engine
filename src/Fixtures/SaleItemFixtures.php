<?php

namespace App\Fixtures;

use App\Entity\SaleItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SaleItemFixtures extends Fixture implements DependentFixtureInterface
{
    public const BARCODE_1 = '3132326554';
    public const BARCODE_2 = '4235235235';
    public const BARCODE_3 = '3812903813';
    public const BARCODE_4 = '3124124124';

    public const REFERENCE_PREFIX = 'sale-item-';

    public function load(ObjectManager $manager)
    {
        /* -------- Sale Item 1 --------- */
        $saleItem1 = new SaleItem();
        $saleItem1->setBarcode(self::BARCODE_1);
        $saleItem1->setTitle('King Kong');
        $saleItem1->setDescription('A movie about a gorilla');
        $saleItem1->setCategory($this->getReference(sprintf("%s%s", SaleItemCategoryFixtures::REFERENCE_PREFIX, SaleItemCategoryFixtures::TICKET_TYPE)));
        $saleItem1->setPrice(5);
        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, self::BARCODE_1), $saleItem1);

        $manager->persist($saleItem1);
        /* -------- End Sale Item 1 --------- */


        /* -------- Sale Item 2 --------- */
        $saleItem2 = new SaleItem();
        $saleItem2->setBarcode(self::BARCODE_2);
        $saleItem2->setTitle('Black T-Shirt');
        $saleItem2->setDescription('Black t-shirt in silk');
        $saleItem2->setCategory($this->getReference(sprintf("%s%s", SaleItemCategoryFixtures::REFERENCE_PREFIX, SaleItemCategoryFixtures::MERCHANDISE_TYPE)));
        $saleItem2->setPrice(3);
        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, self::BARCODE_2), $saleItem2);

        $manager->persist($saleItem2);
        /* -------- End Sale Item 2 --------- */


        /* -------- Sale Item 3--------- */
        $saleItem3 = new SaleItem();
        $saleItem3->setBarcode(self::BARCODE_3);
        $saleItem3->setTitle('Popcorn');
        $saleItem3->setCategory($this->getReference(sprintf("%s%s", SaleItemCategoryFixtures::REFERENCE_PREFIX, SaleItemCategoryFixtures::REFRESHMENTS_TYPE)));
        $saleItem3->setPrice(2);
        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, self::BARCODE_3), $saleItem3);

        $manager->persist($saleItem3);
        /* -------- End Sale Item 3 --------- */


        /* -------- Sale Item 4--------- */
        $saleItem4 = new SaleItem();
        $saleItem4->setBarcode(self::BARCODE_4);
        $saleItem4->setTitle('Harry Potter and deathly hollows Pt2');
        $saleItem4->setDescription('Last movie of series');
        $saleItem4->setCategory($this->getReference(sprintf("%s%s", SaleItemCategoryFixtures::REFERENCE_PREFIX, SaleItemCategoryFixtures::TICKET_TYPE)));
        $saleItem4->setPrice(8);
        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, self::BARCODE_4), $saleItem4);

        $manager->persist($saleItem4);
        /* -------- End Sale Item 4 --------- */

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SaleItemCategoryFixtures::class
        ];
    }
}

<?php

namespace App\Fixtures;

use App\Entity\ShoppingCartItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ShoppingCartItemFixtures extends Fixture implements DependentFixtureInterface
{
    public const TICKET_TYPE = 'ticket';
    public const REFRESHMENTS_TYPE = 'refreshments';
    public const MERCHANDISE_TYPE = 'merchandise';

    public const REFERENCE_PREFIX = 'sale-item-category-';

    public function load(ObjectManager $manager)
    {
        /* -------- Sale Shopping Cart Item 1 --------- */
        $shoppingCartItem1 = new ShoppingCartItem();
        $shoppingCartItem1->setSaleItemId($this->getReference(sprintf("%s%s", SaleItemFixtures::REFERENCE_PREFIX, SaleItemFixtures::BARCODE_1)));
        $shoppingCartItem1->setShoppingCartId($this->getReference(sprintf("%s%d", ShoppingCartFixtures::REFERENCE_PREFIX, 1)));
        $shoppingCartItem1->setQuantity(4);
        $shoppingCartItem1->setPriceNetUnit(5);
        $shoppingCartItem1->setTaxRate(23);

        $manager->persist($shoppingCartItem1);
        /* -------- End Shopping Cart Item 1 --------- */


        /* -------- Sale Shopping Cart Item 2 --------- */
        $shoppingCartItem2 = new ShoppingCartItem();
        $shoppingCartItem2->setSaleItemId($this->getReference(sprintf("%s%s", SaleItemFixtures::REFERENCE_PREFIX, SaleItemFixtures::BARCODE_2)));
        $shoppingCartItem2->setShoppingCartId($this->getReference(sprintf("%s%d", ShoppingCartFixtures::REFERENCE_PREFIX, 1)));
        $shoppingCartItem2->setQuantity(2);
        $shoppingCartItem2->setPriceNetUnit(3);
        $shoppingCartItem2->setTaxRate(6);

        $manager->persist($shoppingCartItem2);
        /* -------- End Shopping Cart Item 2 --------- */


        /* -------- Sale Shopping Cart Item 3 --------- */
        $shoppingCartItem3 = new ShoppingCartItem();
        $shoppingCartItem3->setSaleItemId($this->getReference(sprintf("%s%s", SaleItemFixtures::REFERENCE_PREFIX, SaleItemFixtures::BARCODE_3)));
        $shoppingCartItem3->setShoppingCartId($this->getReference(sprintf("%s%d", ShoppingCartFixtures::REFERENCE_PREFIX, 1)));
        $shoppingCartItem3->setQuantity(2);
        $shoppingCartItem3->setPriceNetUnit(2);
        $shoppingCartItem3->setTaxRate(13);

        $manager->persist($shoppingCartItem3);
        /* -------- End Shopping Cart Item 3 --------- */


        /* -------- Sale Shopping Cart Item 4 --------- */
        $shoppingCartItem4 = new ShoppingCartItem();
        $shoppingCartItem4->setSaleItemId($this->getReference(sprintf("%s%s", SaleItemFixtures::REFERENCE_PREFIX, SaleItemFixtures::BARCODE_4)));
        $shoppingCartItem4->setShoppingCartId($this->getReference(sprintf("%s%d", ShoppingCartFixtures::REFERENCE_PREFIX, 2)));
        $shoppingCartItem4->setQuantity(4);
        $shoppingCartItem4->setPriceNetUnit(5);
        $shoppingCartItem4->setTaxRate(23);

        $manager->persist($shoppingCartItem4);
        /* -------- End Shopping Cart Item 4 --------- */


        /* -------- Sale Shopping Cart Item 5 --------- */
        $shoppingCartItem5 = new ShoppingCartItem();
        $shoppingCartItem5->setSaleItemId($this->getReference(sprintf("%s%s", SaleItemFixtures::REFERENCE_PREFIX, SaleItemFixtures::BARCODE_2)));
        $shoppingCartItem5->setShoppingCartId($this->getReference(sprintf("%s%d", ShoppingCartFixtures::REFERENCE_PREFIX, 2)));
        $shoppingCartItem5->setQuantity(1);
        $shoppingCartItem5->setPriceNetUnit(3);
        $shoppingCartItem5->setTaxRate(6);

        $manager->persist($shoppingCartItem5);
        /* -------- End Shopping Cart Item 5 --------- */


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SaleItemFixtures::class,
            ShoppingCartFixtures::class,
        ];
    }
}

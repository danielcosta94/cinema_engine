<?php

namespace App\Fixtures;

use App\Entity\ShoppingCart;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ShoppingCartFixtures extends Fixture
{
    public const REFERENCE_PREFIX = 'shopping-cart-';

    public function load(ObjectManager $manager)
    {
        $shoppingCart1 = new ShoppingCart();
        $shoppingCart1->setPurchaseAt(new \DateTime());
        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, 1), $shoppingCart1);

        $manager->persist($shoppingCart1);

        $shoppingCart2 = new ShoppingCart();
        $shoppingCart2->setPurchaseAt(new \DateTime());
        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, 2), $shoppingCart2);

        $manager->persist($shoppingCart2);

        $manager->flush();
    }
}

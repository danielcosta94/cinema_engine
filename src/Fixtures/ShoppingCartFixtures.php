<?php

namespace App\Fixtures;

use App\Entity\ShoppingCart;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ShoppingCartFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_PREFIX = 'shopping-cart-';

    public function load(ObjectManager $manager)
    {
        $shoppingCart1 = new ShoppingCart();
        $shoppingCart1->setPurchaseAt(new \DateTime('2021-01-06 12:00:32'));
        $shoppingCart1->setVoucher($this->getReference(sprintf("%s%s", VoucherFixtures::REFERENCE_PREFIX, VoucherFixtures::VOUCHER_1)));

        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, 1), $shoppingCart1);

        $manager->persist($shoppingCart1);

        $shoppingCart2 = new ShoppingCart();
        $shoppingCart2->setPurchaseAt(new \DateTime('2021-01-08 18:22:11'));
        $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, 2), $shoppingCart2);

        $manager->persist($shoppingCart2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VoucherFixtures::class
        ];
    }
}

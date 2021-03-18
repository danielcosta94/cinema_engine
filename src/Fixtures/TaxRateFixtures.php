<?php

namespace App\Fixtures;

use App\Entity\TaxRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxRateFixtures extends Fixture
{
    private const TAX_RATE_FIELD = 'tax_rate';
    private const DESCRIPTION_FIELD = 'hex_code';

    public const REFERENCE_PREFIX = 'tax-rate-';

    public function load(ObjectManager $manager)
    {
        foreach ($this->dummyData() as $key => $data) {
            $taxRate = new TaxRate();
            $taxRate->setTaxRate($data[self::TAX_RATE_FIELD]);
            $taxRate->setDescription($data[self::DESCRIPTION_FIELD]);
            $this->addReference(sprintf("%s%d", self::REFERENCE_PREFIX, $key + 1), $taxRate);

            $manager->persist($taxRate);
            $manager->flush();
        }
    }

    private function dummyData(): array
    {
        return [
            [
                self::TAX_RATE_FIELD => 23,
                self::DESCRIPTION_FIELD => 'High tax rate',
            ],
            [
                self::TAX_RATE_FIELD => 13,
                self::DESCRIPTION_FIELD => 'Medium tax rate',
            ],
            [
                self::TAX_RATE_FIELD => 6,
                self::DESCRIPTION_FIELD => 'Low tax rate',
            ],
        ];
    }
}

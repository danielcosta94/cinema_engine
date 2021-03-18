<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318170511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_carts ADD voucher_id INT DEFAULT NULL AFTER id, ADD purchase_at DATETIME NOT NULL AFTER voucher_id');
        $this->addSql('ALTER TABLE shopping_carts ADD CONSTRAINT FK_4FA232F628AA1B6F FOREIGN KEY (voucher_id) REFERENCES vouchers (id)');
        $this->addSql('CREATE INDEX IDX_4FA232F628AA1B6F ON shopping_carts (voucher_id)');
        $this->addSql('ALTER TABLE vouchers DROP FOREIGN KEY FK_9315074845F80CD');
        $this->addSql('DROP INDEX IDX_9315074845F80CD ON vouchers');
        $this->addSql('ALTER TABLE vouchers DROP shopping_cart_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_carts DROP FOREIGN KEY FK_4FA232F628AA1B6F');
        $this->addSql('DROP INDEX IDX_4FA232F628AA1B6F ON shopping_carts');
        $this->addSql('ALTER TABLE shopping_carts DROP voucher_id, DROP purchase_at');
        $this->addSql('ALTER TABLE vouchers ADD shopping_cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vouchers ADD CONSTRAINT FK_9315074845F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_carts (id)');
        $this->addSql('CREATE INDEX IDX_9315074845F80CD ON vouchers (shopping_cart_id)');
    }
}

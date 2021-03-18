<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318143606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sale_item_categories (id INT AUTO_INCREMENT NOT NULL, tax_rate INT NOT NULL, type VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX description_idx (description), INDEX tax_rate_idx (tax_rate), UNIQUE INDEX type_unique (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale_items (id INT AUTO_INCREMENT NOT NULL, category INT NOT NULL, barcode VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX title_idx (title), INDEX price_idx (price), INDEX category_idx (category), UNIQUE INDEX barcode_unique (barcode), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_cart_items (id INT AUTO_INCREMENT NOT NULL, shopping_cart INT NOT NULL, sale_item INT NOT NULL, quantity INT NOT NULL, price_net_unit DOUBLE PRECISION NOT NULL, tax_rate DOUBLE PRECISION NOT NULL, INDEX shopping_cart_idx (shopping_cart), INDEX sale_item_idx (sale_item), UNIQUE INDEX shopping_cart_item_unique (shopping_cart, sale_item), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_carts (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_rates (id INT AUTO_INCREMENT NOT NULL, tax_rate DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vouchers (id INT AUTO_INCREMENT NOT NULL, shopping_cart_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_9315074845F80CD (shopping_cart_id), UNIQUE INDEX code_unique (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale_item_categories ADD CONSTRAINT FK_B98B5F18C36330C1 FOREIGN KEY (tax_rate) REFERENCES tax_rates (id)');
        $this->addSql('ALTER TABLE sale_items ADD CONSTRAINT FK_31C2B1CE64C19C1 FOREIGN KEY (category) REFERENCES sale_item_categories (id)');
        $this->addSql('ALTER TABLE shopping_cart_items ADD CONSTRAINT FK_A13B631372AAD4F6 FOREIGN KEY (shopping_cart) REFERENCES shopping_carts (id)');
        $this->addSql('ALTER TABLE shopping_cart_items ADD CONSTRAINT FK_A13B6313A35551FB FOREIGN KEY (sale_item) REFERENCES sale_items (id)');
        $this->addSql('ALTER TABLE vouchers ADD CONSTRAINT FK_9315074845F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_carts (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale_items DROP FOREIGN KEY FK_31C2B1CE64C19C1');
        $this->addSql('ALTER TABLE shopping_cart_items DROP FOREIGN KEY FK_A13B6313A35551FB');
        $this->addSql('ALTER TABLE shopping_cart_items DROP FOREIGN KEY FK_A13B631372AAD4F6');
        $this->addSql('ALTER TABLE vouchers DROP FOREIGN KEY FK_9315074845F80CD');
        $this->addSql('ALTER TABLE sale_item_categories DROP FOREIGN KEY FK_B98B5F18C36330C1');
        $this->addSql('DROP TABLE sale_item_categories');
        $this->addSql('DROP TABLE sale_items');
        $this->addSql('DROP TABLE shopping_cart_items');
        $this->addSql('DROP TABLE shopping_carts');
        $this->addSql('DROP TABLE tax_rates');
        $this->addSql('DROP TABLE vouchers');
    }
}

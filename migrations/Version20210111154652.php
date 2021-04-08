<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111154652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sale_collection_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sale_collection (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, cover VARCHAR(255) DEFAULT NULL, valid_until TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, products_price TEXT DEFAULT NULL, is_hidden BOOLEAN NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sale_collection_product (sale_collection_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(sale_collection_id, product_id))');
        $this->addSql('CREATE INDEX IDX_FAE5E66270CED019 ON sale_collection_product (sale_collection_id)');
        $this->addSql('CREATE INDEX IDX_FAE5E6624584665A ON sale_collection_product (product_id)');
        $this->addSql('ALTER TABLE sale_collection_product ADD CONSTRAINT FK_FAE5E66270CED019 FOREIGN KEY (sale_collection_id) REFERENCES sale_collection (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale_collection_product ADD CONSTRAINT FK_FAE5E6624584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promo_code ALTER value SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sale_collection_product DROP CONSTRAINT FK_FAE5E66270CED019');
        $this->addSql('DROP SEQUENCE sale_collection_id_seq CASCADE');
        $this->addSql('DROP TABLE sale_collection');
        $this->addSql('DROP TABLE sale_collection_product');
        $this->addSql('ALTER TABLE promo_code ALTER value DROP NOT NULL');
    }
}

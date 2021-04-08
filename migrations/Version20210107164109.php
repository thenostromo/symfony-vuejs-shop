<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107164109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_order ADD is_deleted BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD is_deleted BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD is_hidden BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD is_deleted BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE category DROP editable');
        $this->addSql('ALTER TABLE product ADD is_hidden BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD is_deleted BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD medium_images VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD large_images VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product DROP active');
        $this->addSql('ALTER TABLE product DROP hidden');
        $this->addSql('ALTER TABLE product RENAME COLUMN cover TO small_images');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category ADD editable BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE category DROP is_hidden');
        $this->addSql('ALTER TABLE category DROP is_deleted');
        $this->addSql('ALTER TABLE product ADD cover VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE product ADD hidden BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE product DROP is_hidden');
        $this->addSql('ALTER TABLE product DROP is_deleted');
        $this->addSql('ALTER TABLE product DROP small_images');
        $this->addSql('ALTER TABLE product DROP medium_images');
        $this->addSql('ALTER TABLE product DROP large_images');
        $this->addSql('ALTER TABLE app_order DROP is_deleted');
        $this->addSql('ALTER TABLE app_user DROP is_deleted');
    }
}

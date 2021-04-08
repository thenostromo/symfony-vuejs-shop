<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106132219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_user ADD full_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD phone VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD zipcode INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app_user DROP full_name');
        $this->addSql('ALTER TABLE app_user DROP phone');
        $this->addSql('ALTER TABLE app_user DROP address');
        $this->addSql('ALTER TABLE app_user DROP zipcode');
    }
}

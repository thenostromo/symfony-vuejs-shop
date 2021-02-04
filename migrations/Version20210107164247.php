<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107164247 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_order ALTER is_deleted SET NOT NULL');
        $this->addSql('ALTER TABLE app_user ALTER is_deleted SET NOT NULL');
        $this->addSql('ALTER TABLE category ALTER is_hidden SET NOT NULL');
        $this->addSql('ALTER TABLE category ALTER is_deleted SET NOT NULL');
        $this->addSql('ALTER TABLE product ALTER is_hidden SET NOT NULL');
        $this->addSql('ALTER TABLE product ALTER is_deleted SET NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category ALTER is_hidden DROP NOT NULL');
        $this->addSql('ALTER TABLE category ALTER is_deleted DROP NOT NULL');
        $this->addSql('ALTER TABLE product ALTER is_hidden DROP NOT NULL');
        $this->addSql('ALTER TABLE product ALTER is_deleted DROP NOT NULL');
        $this->addSql('ALTER TABLE app_order ALTER is_deleted DROP NOT NULL');
        $this->addSql('ALTER TABLE app_user ALTER is_deleted DROP NOT NULL');
    }
}

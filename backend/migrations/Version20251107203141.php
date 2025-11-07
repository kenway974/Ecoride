<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107203141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT fk_7656f53bf5b7af75');
        $this->addSql('DROP INDEX idx_7656f53bf5b7af75');
        $this->addSql('ALTER TABLE trip ADD start_city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trip ADD arrival_city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trip ADD start_address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trip ADD arrival_address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trip DROP address_id');
        $this->addSql('ALTER TABLE trip DROP distance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trip ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD distance DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE trip DROP start_city');
        $this->addSql('ALTER TABLE trip DROP arrival_city');
        $this->addSql('ALTER TABLE trip DROP start_address');
        $this->addSql('ALTER TABLE trip DROP arrival_address');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT fk_7656f53bf5b7af75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7656f53bf5b7af75 ON trip (address_id)');
    }
}

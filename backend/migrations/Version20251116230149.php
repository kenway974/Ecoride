<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251116230149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trip_user (trip_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(trip_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A6AB4522A5BC2E0E ON trip_user (trip_id)');
        $this->addSql('CREATE INDEX IDX_A6AB4522A76ED395 ON trip_user (user_id)');
        $this->addSql('ALTER TABLE trip_user ADD CONSTRAINT FK_A6AB4522A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip_user ADD CONSTRAINT FK_A6AB4522A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE trip ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE trip ALTER start_address DROP NOT NULL');
        $this->addSql('ALTER TABLE trip ALTER arrival_address DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN trip.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN trip.updated_at IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trip_user DROP CONSTRAINT FK_A6AB4522A5BC2E0E');
        $this->addSql('ALTER TABLE trip_user DROP CONSTRAINT FK_A6AB4522A76ED395');
        $this->addSql('DROP TABLE trip_user');
        $this->addSql('ALTER TABLE trip ALTER start_address SET NOT NULL');
        $this->addSql('ALTER TABLE trip ALTER arrival_address SET NOT NULL');
        $this->addSql('ALTER TABLE trip ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE trip ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN trip.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN trip.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }
}

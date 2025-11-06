<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251106200215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id SERIAL NOT NULL, start_adress VARCHAR(255) NOT NULL, start_city VARCHAR(50) NOT NULL, arrival_address VARCHAR(255) NOT NULL, arrival_city VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contact (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, email VARCHAR(10) NOT NULL, subject VARCHAR(10) NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN contact.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE preference (id_pref SERIAL NOT NULL, user_id INT NOT NULL, animals BOOLEAN NOT NULL, smoke BOOLEAN NOT NULL, food BOOLEAN NOT NULL, is_custom BOOLEAN NOT NULL, options JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id_pref))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D69B053A76ED395 ON preference (user_id)');
        $this->addSql('CREATE TABLE reservation (id SERIAL NOT NULL, trip_id INT NOT NULL, passenger_id INT NOT NULL, seats_booked INT NOT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C84955A5BC2E0E ON reservation (trip_id)');
        $this->addSql('CREATE INDEX IDX_42C849554502E565 ON reservation (passenger_id)');
        $this->addSql('COMMENT ON COLUMN reservation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reservation.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE revies (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE review (id SERIAL NOT NULL, author_id INT NOT NULL, driver_id INT NOT NULL, moderated_by_id INT DEFAULT NULL, rating INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
        $this->addSql('CREATE INDEX IDX_794381C6C3423909 ON review (driver_id)');
        $this->addSql('CREATE INDEX IDX_794381C68EDA19B0 ON review (moderated_by_id)');
        $this->addSql('COMMENT ON COLUMN review.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN review.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE trip (id SERIAL NOT NULL, driver_id INT NOT NULL, vehicle_id INT NOT NULL, address_id INT NOT NULL, departure_time TIME(0) WITHOUT TIME ZONE NOT NULL, departure_date DATE NOT NULL, arrival_date DATE NOT NULL, arrival_time TIME(0) WITHOUT TIME ZONE NOT NULL, seats_remaining INT NOT NULL, is_ecological BOOLEAN NOT NULL, status VARCHAR(20) NOT NULL, description TEXT DEFAULT NULL, luggage TEXT DEFAULT NULL, distance DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7656F53BC3423909 ON trip (driver_id)');
        $this->addSql('CREATE INDEX IDX_7656F53B545317D1 ON trip (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_7656F53BF5B7AF75 ON trip (address_id)');
        $this->addSql('COMMENT ON COLUMN trip.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN trip.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(50) NOT NULL, bio TEXT DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, credits INT NOT NULL, is_active BOOLEAN NOT NULL, photo VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE vehicle (id SERIAL NOT NULL, owner_id INT NOT NULL, plate VARCHAR(20) NOT NULL, brand VARCHAR(50) NOT NULL, model VARCHAR(50) NOT NULL, release_year INT NOT NULL, energy VARCHAR(20) NOT NULL, seats_total INT NOT NULL, seats_available INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B80E4867E3C61F9 ON vehicle (owner_id)');
        $this->addSql('COMMENT ON COLUMN vehicle.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vehicle.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B053A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554502E565 FOREIGN KEY (passenger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6C3423909 FOREIGN KEY (driver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68EDA19B0 FOREIGN KEY (moderated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BC3423909 FOREIGN KEY (driver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE preference DROP CONSTRAINT FK_5D69B053A76ED395');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A5BC2E0E');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849554502E565');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6C3423909');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C68EDA19B0');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53BC3423909');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53B545317D1');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53BF5B7AF75');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E4867E3C61F9');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE preference');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE revies');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE vehicle');
    }
}

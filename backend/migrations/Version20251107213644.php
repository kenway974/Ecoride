<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107213644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE preference_id_pref_seq CASCADE');
        $this->addSql('ALTER TABLE preference DROP CONSTRAINT preference_pkey');
        $this->addSql('ALTER TABLE preference RENAME COLUMN id_pref TO id');
        $this->addSql('ALTER TABLE preference ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE "user" ADD preference_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649D81022C0 FOREIGN KEY (preference_id) REFERENCES preference (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D81022C0 ON "user" (preference_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE preference_id_pref_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649D81022C0');
        $this->addSql('DROP INDEX UNIQ_8D93D649D81022C0');
        $this->addSql('ALTER TABLE "user" DROP preference_id');
        $this->addSql('DROP INDEX preference_pkey');
        $this->addSql('ALTER TABLE preference RENAME COLUMN id TO id_pref');
        $this->addSql('ALTER TABLE preference ADD PRIMARY KEY (id_pref)');
    }
}

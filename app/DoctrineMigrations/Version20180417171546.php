<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417171546 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE babysitters DROP CONSTRAINT fk_1cfd051b8bac62af');
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE events_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE events (id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_enabled BOOLEAN NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE stage (id INT NOT NULL, event_id INT NOT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, count_required_members INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C27C936971F7E88B ON stage (event_id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C936971F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP INDEX idx_1cfd051b8bac62af');
        $this->addSql('ALTER TABLE babysitters RENAME COLUMN city_id TO event_id');
        $this->addSql('ALTER TABLE babysitters ADD CONSTRAINT FK_1CFD051B71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1CFD051B71F7E88B ON babysitters (event_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE babysitters DROP CONSTRAINT FK_1CFD051B71F7E88B');
        $this->addSql('ALTER TABLE stage DROP CONSTRAINT FK_C27C936971F7E88B');
        $this->addSql('DROP SEQUENCE events_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stage_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE city (id INT NOT NULL, name VARCHAR(255) NOT NULL, is_enabled BOOLEAN NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP INDEX IDX_1CFD051B71F7E88B');
        $this->addSql('ALTER TABLE babysitters RENAME COLUMN event_id TO city_id');
        $this->addSql('ALTER TABLE babysitters ADD CONSTRAINT fk_1cfd051b8bac62af FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1cfd051b8bac62af ON babysitters (city_id)');
    }
}

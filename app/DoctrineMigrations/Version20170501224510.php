<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170501224510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE babysitters_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE t_shirt_size_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE babysitters (id INT NOT NULL, city_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(20) NOT NULL, allow_terms BOOLEAN NOT NULL, allow_marketing BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1CFD051B8BAC62AF ON babysitters (city_id)');
        $this->addSql('CREATE TABLE city (id INT NOT NULL, name VARCHAR(255) NOT NULL, is_enabled BOOLEAN NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE member (id INT NOT NULL, babysitter_id INT NOT NULL, t_shirt_size_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, born_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, prohibited_food VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70E4FA785732FB3C ON member (babysitter_id)');
        $this->addSql('CREATE INDEX IDX_70E4FA782F81300D ON member (t_shirt_size_id)');
        $this->addSql('CREATE TABLE t_shirt_size (id INT NOT NULL, name VARCHAR(255) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE babysitters ADD CONSTRAINT FK_1CFD051B8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA785732FB3C FOREIGN KEY (babysitter_id) REFERENCES babysitters (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA782F81300D FOREIGN KEY (t_shirt_size_id) REFERENCES t_shirt_size (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE member DROP CONSTRAINT FK_70E4FA785732FB3C');
        $this->addSql('ALTER TABLE babysitters DROP CONSTRAINT FK_1CFD051B8BAC62AF');
        $this->addSql('ALTER TABLE member DROP CONSTRAINT FK_70E4FA782F81300D');
        $this->addSql('DROP SEQUENCE babysitters_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE t_shirt_size_id_seq CASCADE');
        $this->addSql('DROP TABLE babysitters');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE t_shirt_size');
    }
}

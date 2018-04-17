<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417175540 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE member DROP CONSTRAINT fk_70e4fa782f81300d');
        $this->addSql('DROP SEQUENCE member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE t_shirt_size_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE food_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stage_id_seq CASCADE');
        $this->addSql('CREATE TABLE groups (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, begin_old_member INT NOT NULL, end_old_member INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE members (id SERIAL NOT NULL, babysitter_id INT NOT NULL, group_id INT NOT NULL, t_shirt_size_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, born_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, prohibited_food VARCHAR(255) NOT NULL, confirmed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_45A0D2FF5732FB3C ON members (babysitter_id)');
        $this->addSql('CREATE INDEX IDX_45A0D2FFFE54D947 ON members (group_id)');
        $this->addSql('CREATE INDEX IDX_45A0D2FF2F81300D ON members (t_shirt_size_id)');
        $this->addSql('CREATE TABLE foods (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3803909D5E237E06 ON foods (name)');
        $this->addSql('CREATE TABLE t_shirt_sizes (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE stages (id SERIAL NOT NULL, event_id INT NOT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, count_required_members INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FA26A6471F7E88B ON stages (event_id)');
        $this->addSql('ALTER TABLE members ADD CONSTRAINT FK_45A0D2FF5732FB3C FOREIGN KEY (babysitter_id) REFERENCES babysitters (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE members ADD CONSTRAINT FK_45A0D2FFFE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE members ADD CONSTRAINT FK_45A0D2FF2F81300D FOREIGN KEY (t_shirt_size_id) REFERENCES t_shirt_sizes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stages ADD CONSTRAINT FK_2FA26A6471F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE t_shirt_size');
        $this->addSql('DROP TABLE food');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE stage');
        $this->addSql('SELECT setval(\'events_id_seq\', (SELECT MAX(id) FROM events))');
        $this->addSql('ALTER TABLE events ALTER id SET DEFAULT nextval(\'events_id_seq\')');
        $this->addSql('SELECT setval(\'babysitters_id_seq\', (SELECT MAX(id) FROM babysitters))');
        $this->addSql('ALTER TABLE babysitters ALTER id SET DEFAULT nextval(\'babysitters_id_seq\')');

        $this->addSql('INSERT INTO groups(name,begin_old_member,end_old_member) VALUES(\'6+\',5,7),(\'8+\',8,9),(\'10+\',10,11),(\'12+\',12,15)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE members DROP CONSTRAINT FK_45A0D2FFFE54D947');
        $this->addSql('ALTER TABLE members DROP CONSTRAINT FK_45A0D2FF2F81300D');
        $this->addSql('CREATE SEQUENCE member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE t_shirt_size_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE food_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE t_shirt_size (id INT NOT NULL, name VARCHAR(255) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE food (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_d43829f75e237e06 ON food (name)');
        $this->addSql('CREATE TABLE member (id INT NOT NULL, babysitter_id INT NOT NULL, t_shirt_size_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, born_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, prohibited_food VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, confirmed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_70e4fa782f81300d ON member (t_shirt_size_id)');
        $this->addSql('CREATE INDEX idx_70e4fa785732fb3c ON member (babysitter_id)');
        $this->addSql('CREATE TABLE stage (id INT NOT NULL, event_id INT NOT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, count_required_members INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c27c936971f7e88b ON stage (event_id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT fk_70e4fa785732fb3c FOREIGN KEY (babysitter_id) REFERENCES babysitters (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT fk_70e4fa782f81300d FOREIGN KEY (t_shirt_size_id) REFERENCES t_shirt_size (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT fk_c27c936971f7e88b FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE groups');
        $this->addSql('DROP TABLE members');
        $this->addSql('DROP TABLE foods');
        $this->addSql('DROP TABLE t_shirt_sizes');
        $this->addSql('DROP TABLE stages');
        $this->addSql('ALTER TABLE babysitters ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE events ALTER id DROP DEFAULT');
    }
}

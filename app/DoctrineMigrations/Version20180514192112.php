<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180514192112 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO status(id,name) VALUES(1,\'Registered\'),(2,\'Expectant\'),(3,\'Rejected\'),(4,\'Confirmed\'),(5,\'Waiting on confirm\'),(6,\'Final confirmed\');');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B00651C5E237E06 ON status (name)');
        $this->addSql('ALTER TABLE members ADD status_id INT NOT NULL DEFAULT 1');
        $this->addSql('CREATE INDEX IDX_45A0D2FF6BF700BD ON members (status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP INDEX IDX_45A0D2FF6BF700BD');
        $this->addSql('ALTER TABLE members DROP status_id');
    }
}

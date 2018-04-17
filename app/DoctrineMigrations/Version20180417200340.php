<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417200340 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE members DROP confirmed_mail_at');
        $this->addSql('ALTER TABLE babysitters ADD confirmed_mail_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE babysitters ADD token VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX token_unique_idx ON babysitters (token)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX token_unique_idx');
        $this->addSql('ALTER TABLE babysitters DROP confirmed_mail_at');
        $this->addSql('ALTER TABLE babysitters DROP token');
        $this->addSql('ALTER TABLE members ADD confirmed_mail_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }
}

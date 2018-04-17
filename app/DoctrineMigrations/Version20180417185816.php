<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417185816 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE members ADD is_expectant BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE members RENAME COLUMN confirmed_at TO confirmed_mail_at');

            $this->addSql('INSERT INTO foods(name) VALUES (\'Czekolada\'),(\'Orzechy\'),(\'Migdały\'),
            (\'Gluten\'),(\'Mięso\'),(\'Ryby\'),(\'Jajka\'),(\'Produkty mleczne\'),(\'Soja\');');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE members DROP is_expectant');
        $this->addSql('ALTER TABLE members RENAME COLUMN confirmed_mail_at TO confirmed_at');
        $this->addSql('TRUNCATE foods');
    }
}

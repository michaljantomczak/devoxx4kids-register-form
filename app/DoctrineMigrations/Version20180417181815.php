<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417181815 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql('INSERT INTO t_shirt_sizes(name,updated_at,created_at) VALUES(\'122/128\',now(),now())
        ,(\'134/140\',now(),now())
        ,(\'146/152\',now(),now())
        ,(\'158/164\',now(),now())
        ,(\'170/176\',now(),now())');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->addSql('TRUNCATE t_shirt_sizes;');

    }
}

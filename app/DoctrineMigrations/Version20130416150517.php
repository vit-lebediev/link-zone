<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130416150517 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("CREATE SEQUENCE LogStatusChanges_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE LogStatusChanges (
            id INT NOT NULL,
            userId INT NOT NULL,
            fromStatus VARCHAR(100) NOT NULL,
            toStatus VARCHAR(100) NOT NULL,
            date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
            whoChanged VARCHAR(255) NOT NULL,
            reason VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("DROP SEQUENCE LogStatusChanges_id_seq");
        $this->addSql("DROP TABLE LogStatusChanges");
    }
}

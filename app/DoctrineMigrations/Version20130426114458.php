<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130426114458 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE platforms.requests ADD sender_link_location VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE platforms.requests ADD receiver_link_location VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE platforms.requests ADD sender_accepted BOOLEAN DEFAULT FALSE");
        $this->addSql("ALTER TABLE platforms.requests ADD receiver_accepted BOOLEAN DEFAULT FALSE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE platforms.requests DROP sender_link_location");
        $this->addSql("ALTER TABLE platforms.requests DROP receiver_link_location");
        $this->addSql("ALTER TABLE platforms.requests DROP sender_accepted");
        $this->addSql("ALTER TABLE platforms.requests DROP receiver_accepted");
    }
}

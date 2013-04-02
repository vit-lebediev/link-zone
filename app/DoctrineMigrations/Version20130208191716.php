<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130208191716 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE users ADD bonus DOUBLE PRECISION DEFAULT 0 NOT NULL");
        $this->addSql("ALTER TABLE users ADD billingYaDengy VARCHAR(20) DEFAULT NULL");
        $this->addSql("ALTER TABLE users ADD billingWMR VARCHAR(20) DEFAULT NULL");
        $this->addSql("ALTER TABLE users ADD billingWMZ VARCHAR(20) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE users DROP bonus");
        $this->addSql("ALTER TABLE users DROP billingYaDengy");
        $this->addSql("ALTER TABLE users DROP billingWMR");
        $this->addSql("ALTER TABLE users DROP billingWMZ");
    }
}

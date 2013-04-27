<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130415224107 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE users ADD referralValue VARCHAR(10) NOT NULL");
        $this->addSql("ALTER TABLE users ADD referrerId INT DEFAULT NULL");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_1483A5E992C172F9 ON users (referralValue)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE users DROP referralValue");
        $this->addSql("ALTER TABLE users DROP referrerId");
        $this->addSql("DROP INDEX UNIQ_1483A5E992C172F9");
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130418013305 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("DROP SEQUENCE logstatuschanges_id_seq");
        $this->addSql("DROP TABLE logstatuschanges");

        $this->addSql("CREATE SEQUENCE log_status_changes_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE platform_topics_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE platforms_id_seq INCREMENT BY 1 MINVALUE 1 START 1");

        $this->addSql("CREATE TABLE log_status_changes (
            id INT NOT NULL,
            userId INT NOT NULL,
            fromStatus VARCHAR(100) NOT NULL,
            toStatus VARCHAR(100) NOT NULL,
            date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            whoChanged VARCHAR(255) NOT NULL,
            reason VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )");
        $this->addSql("CREATE TABLE platform_topics (
            id INT NOT NULL,
            description VARCHAR(255) NOT NULL,
            transKey VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )");
        $this->addSql("CREATE TABLE platforms (
            id INT NOT NULL,
            topic INT DEFAULT NULL,
            url VARCHAR(1000) NOT NULL,
            description VARCHAR(2000) NOT NULL,
            status VARCHAR(100) NOT NULL,
            created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            userId INT DEFAULT NULL,
            PRIMARY KEY(id)
        )");

        $this->addSql("CREATE INDEX IDX_178186E364B64DCC ON platforms (userId)");
        $this->addSql("CREATE INDEX IDX_178186E39D40DE1B ON platforms (topic)");

        $this->addSql("ALTER TABLE platforms ADD CONSTRAINT FK_178186E364B64DCC FOREIGN KEY (userId) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE platforms ADD CONSTRAINT FK_178186E39D40DE1B FOREIGN KEY (topic) REFERENCES platform_topics (id) NOT DEFERRABLE INITIALLY IMMEDIATE");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE platforms DROP CONSTRAINT FK_178186E39D40DE1B");
        $this->addSql("DROP SEQUENCE log_status_changes_id_seq");
        $this->addSql("DROP SEQUENCE platform_topics_id_seq");
        $this->addSql("DROP SEQUENCE platforms_id_seq");
        $this->addSql("CREATE SEQUENCE logstatuschanges_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE logstatuschanges (
            id INT NOT NULL,
            userid INT NOT NULL,
            fromstatus VARCHAR(100) NOT NULL,
            tostatus VARCHAR(100) NOT NULL,
            date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT 'now()' NOT NULL,
            whochanged VARCHAR(255) NOT NULL,
            reason VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )");
        $this->addSql("DROP TABLE log_status_changes");
        $this->addSql("DROP TABLE platform_topics");
        $this->addSql("DROP TABLE platforms");
    }
}

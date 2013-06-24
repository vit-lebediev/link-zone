<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130424114034 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("CREATE SEQUENCE platforms.requests_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE platforms.requests (
            id INT NOT NULL,
            sender_user INT DEFAULT NULL,
            sender_platform INT DEFAULT NULL,
            receiver_user INT DEFAULT NULL,
            receiver_platform INT DEFAULT NULL,
            sender_link VARCHAR(255) NOT NULL,
            sender_link_text VARCHAR(255) NOT NULL,
            receiver_link VARCHAR(255) DEFAULT NULL,
            receiver_link_text VARCHAR(255) DEFAULT NULL,
            status VARCHAR(255) NOT NULL,
            created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            finished TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )");
        $this->addSql("CREATE INDEX IDX_F42AB6037279209A ON platforms.requests (sender_user)");
        $this->addSql("CREATE INDEX IDX_F42AB60335693D35 ON platforms.requests (sender_platform)");
        $this->addSql("CREATE INDEX IDX_F42AB60374D289ED ON platforms.requests (receiver_user)");
        $this->addSql("CREATE INDEX IDX_F42AB603B1B803E8 ON platforms.requests (receiver_platform)");
        $this->addSql("ALTER TABLE platforms.requests ADD CONSTRAINT FK_F42AB6037279209A FOREIGN KEY (sender_user) REFERENCES users.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE platforms.requests ADD CONSTRAINT FK_F42AB60335693D35 FOREIGN KEY (sender_platform) REFERENCES platforms.platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE platforms.requests ADD CONSTRAINT FK_F42AB60374D289ED FOREIGN KEY (receiver_user) REFERENCES users.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE platforms.requests ADD CONSTRAINT FK_F42AB603B1B803E8 FOREIGN KEY (receiver_platform) REFERENCES platforms.platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("DROP SEQUENCE platforms.requests_id_seq");
        $this->addSql("DROP TABLE platforms.requests");
    }
}

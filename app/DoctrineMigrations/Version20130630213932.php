<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130630213932 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        // Dialogues table
        $this->addSql("CREATE SEQUENCE users.dialogues_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE users.dialogues (
            id INT NOT NULL,
            sender_user_id INT NOT NULL,
            receiver_user_id INT NOT NULL,
            sender_platform_id INT NOT NULL,
            receiver_platform_id INT NOT NULL,
            created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )");
        $this->addSql("CREATE INDEX IDX_3B0C59582A98155E ON users.dialogues (sender_user_id)");
        $this->addSql("CREATE INDEX IDX_3B0C5958DA57E237 ON users.dialogues (receiver_user_id)");
        $this->addSql("CREATE INDEX IDX_3B0C59581A67733C ON users.dialogues (sender_platform_id)");
        $this->addSql("CREATE INDEX IDX_3B0C59581D1D977D ON users.dialogues (receiver_platform_id)");

        // Messages table
        $this->addSql("CREATE SEQUENCE users.messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE users.messages (
            id INT NOT NULL,
            dialog_id INT NOT NULL,
            sender_platform_id INT NOT NULL,
            receiver_platform_id INT NOT NULL,
            message VARCHAR(1000) NOT NULL,
            sent TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )");
        $this->addSql("CREATE INDEX IDX_479BE2DA5E46C4E2 ON users.messages (dialog_id)");
        $this->addSql("CREATE INDEX IDX_479BE2DA1A67733C ON users.messages (sender_platform_id)");
        $this->addSql("CREATE INDEX IDX_479BE2DA1D1D977D ON users.messages (receiver_platform_id)");

        // set references
        $this->addSql("ALTER TABLE users.dialogues ADD CONSTRAINT FK_3B0C59582A98155E FOREIGN KEY (sender_user_id) REFERENCES users.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE users.dialogues ADD CONSTRAINT FK_3B0C5958DA57E237 FOREIGN KEY (receiver_user_id) REFERENCES users.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE users.dialogues ADD CONSTRAINT FK_3B0C59581A67733C FOREIGN KEY (sender_platform_id) REFERENCES platforms.platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE users.dialogues ADD CONSTRAINT FK_3B0C59581D1D977D FOREIGN KEY (receiver_platform_id) REFERENCES platforms.platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE users.messages ADD CONSTRAINT FK_479BE2DA5E46C4E2 FOREIGN KEY (dialog_id) REFERENCES users.dialogues (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE users.messages ADD CONSTRAINT FK_479BE2DA1A67733C FOREIGN KEY (sender_platform_id) REFERENCES platforms.platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE users.messages ADD CONSTRAINT FK_479BE2DA1D1D977D FOREIGN KEY (receiver_platform_id) REFERENCES platforms.platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE users.messages DROP CONSTRAINT FK_479BE2DA5E46C4E2");
        $this->addSql("ALTER TABLE users.messages DROP CONSTRAINT FK_479BE2DA1A67733C");
        $this->addSql("ALTER TABLE users.messages DROP CONSTRAINT FK_479BE2DA1D1D977D");
        $this->addSql("ALTER TABLE users.dialogues DROP CONSTRAINT FK_3B0C59581D1D977D");
        $this->addSql("ALTER TABLE users.dialogues DROP CONSTRAINT FK_3B0C59581A67733C");
        $this->addSql("ALTER TABLE users.dialogues DROP CONSTRAINT FK_3B0C5958DA57E237");
        $this->addSql("ALTER TABLE users.dialogues DROP CONSTRAINT FK_3B0C59582A98155E");
        
        $this->addSql("DROP SEQUENCE users.dialogues_id_seq");
        $this->addSql("DROP SEQUENCE users.messages_id_seq");
        $this->addSql("DROP TABLE users.dialogues");
        $this->addSql("DROP TABLE users.messages");
    }
}

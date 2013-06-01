<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130421205855 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("CREATE SCHEMA platforms");

        $this->addSql("ALTER TABLE platforms SET SCHEMA platforms");
        $this->addSql("ALTER SEQUENCE platforms_id_seq SET SCHEMA platforms");

        $this->addSql("ALTER TABLE platform_topics RENAME TO topics");
        $this->addSql("ALTER TABLE topics SET SCHEMA platforms");
        $this->addSql("ALTER TABLE platform_topics_id_seq RENAME TO topics_id_seq");
        $this->addSql("ALTER SEQUENCE topics_id_seq SET SCHEMA platforms");

        $this->addSql("ALTER TABLE log_platform_status_changes SET SCHEMA platforms");
        $this->addSql("ALTER TABLE platforms.log_platform_status_changes RENAME TO log_status_changes");
        $this->addSql("ALTER SEQUENCE log_platform_status_changes_id_seq SET SCHEMA platforms");
        $this->addSql("ALTER TABLE platforms.log_platform_status_changes_id_seq RENAME TO log_status_changes_id_seq");

        $this->addSql("CREATE SCHEMA users");

        $this->addSql("ALTER TABLE users SET SCHEMA users");
        $this->addSql("ALTER SEQUENCE users_id_seq SET SCHEMA users");

        $this->addSql("ALTER TABLE log_status_changes SET SCHEMA users");
        $this->addSql("ALTER SEQUENCE log_status_changes_id_seq SET SCHEMA users");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE users.log_status_changes SET SCHEMA public");
        $this->addSql("ALTER SEQUENCE users.log_status_changes_id_seq SET SCHEMA public");

        $this->addSql("ALTER TABLE users.users SET SCHEMA public");
        $this->addSql("ALTER SEQUENCE users.users_id_seq SET SCHEMA public");

        $this->addSql("DROP SCHEMA users");

        $this->addSql("ALTER TABLE platforms.log_status_changes RENAME TO log_platform_status_changes");
        $this->addSql("ALTER TABLE platforms.log_platform_status_changes SET SCHEMA public");
        $this->addSql("ALTER TABLE platforms.log_status_changes_id_seq RENAME TO log_platform_status_changes_id_seq");
        $this->addSql("ALTER SEQUENCE platforms.log_platform_status_changes_id_seq SET SCHEMA public");

        $this->addSql("ALTER TABLE platforms.topics SET SCHEMA public");
        $this->addSql("ALTER TABLE topics RENAME TO platform_topics");
        $this->addSql("ALTER SEQUENCE platforms.topics_id_seq SET SCHEMA public");
        $this->addSql("ALTER TABLE topics_id_seq RENAME TO platform_topics_id_seq");

        $this->addSql("ALTER TABLE platforms.platforms SET SCHEMA public");
        $this->addSql("ALTER SEQUENCE platforms.platforms_id_seq SET SCHEMA public");

        $this->addSql("DROP SCHEMA platforms");
    }
}

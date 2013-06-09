<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130422191226 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("CREATE SEQUENCE platforms.tagging_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE platforms.tags_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE platforms.tagging (id INT NOT NULL, tag_id INT DEFAULT NULL, resource_type VARCHAR(50) NOT NULL, resource_id VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX IDX_51A9D0F5BAD26311 ON platforms.tagging (tag_id)");
        $this->addSql("CREATE UNIQUE INDEX tagging_idx ON platforms.tagging (tag_id, resource_type, resource_id)");
        $this->addSql("CREATE TABLE platforms.tags (id INT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_B4198C8B5E237E06 ON platforms.tags (name)");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_B4198C8B989D9B62 ON platforms.tags (slug)");
        $this->addSql("ALTER TABLE platforms.tagging ADD CONSTRAINT FK_51A9D0F5BAD26311 FOREIGN KEY (tag_id) REFERENCES platforms.tags (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE platforms.tagging DROP CONSTRAINT FK_51A9D0F5BAD26311");
        $this->addSql("DROP SEQUENCE platforms.tagging_id_seq");
        $this->addSql("DROP SEQUENCE platforms.tags_id_seq");
        $this->addSql("DROP TABLE platforms.tagging");
        $this->addSql("DROP TABLE platforms.tags");
    }
}

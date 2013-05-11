<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130420002104 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

//        $this->addSql("ALTER TABLE log_platform_status_changes ADD from_status VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_platform_status_changes ADD to_status VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_platform_status_changes DROP fromstatus");
//        $this->addSql("ALTER TABLE log_platform_status_changes DROP tostatus");

        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN fromstatus TO from_status");
        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN tostatus TO to_status");

        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN platformid TO platform_id");
        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN whochanged TO who_changed");

//        $this->addSql("ALTER TABLE log_status_changes ADD from_status VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_status_changes ADD to_status VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_status_changes DROP fromstatus");
//        $this->addSql("ALTER TABLE log_status_changes DROP tostatus");

        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN fromstatus TO from_status");
        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN tostatus TO to_status");

        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN userid TO user_id");
        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN whochanged TO who_changed");
        $this->addSql("ALTER TABLE platform_topics RENAME COLUMN transkey TO trans_key");

//        $this->addSql("ALTER TABLE users ADD billing_ya_dengy VARCHAR(20) DEFAULT NULL");
//        $this->addSql("ALTER TABLE users ADD billing_wmr VARCHAR(20) DEFAULT NULL");
//        $this->addSql("ALTER TABLE users ADD billing_wmz VARCHAR(20) DEFAULT NULL");
//        $this->addSql("ALTER TABLE users DROP billingyadengy");
//        $this->addSql("ALTER TABLE users DROP billingwmr");
//        $this->addSql("ALTER TABLE users DROP billingwmz");

        $this->addSql("ALTER TABLE users RENAME COLUMN billingyadengy TO billing_ya_dengy");
        $this->addSql("ALTER TABLE users RENAME COLUMN billingwmr TO billing_wmr");
        $this->addSql("ALTER TABLE users RENAME COLUMN billingwmz TO billing_wmz");

        $this->addSql("ALTER TABLE users RENAME COLUMN referrerid TO referrer_id");
        $this->addSql("ALTER TABLE users RENAME COLUMN registrationdate TO registration_date");
        $this->addSql("ALTER TABLE users RENAME COLUMN referralvalue TO referral_value");
        $this->addSql("ALTER TABLE users DROP CONSTRAINT fk_1483a5e93dd9c621");
        $this->addSql("DROP INDEX uniq_1483a5e992c172f9");
        $this->addSql("DROP INDEX idx_1483a5e93dd9c621");
        $this->addSql("ALTER TABLE users ADD CONSTRAINT FK_1483A5E9798C22DB FOREIGN KEY (referrer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_1483A5E975B9B9B9 ON users (referral_value)");
        $this->addSql("CREATE INDEX IDX_1483A5E9798C22DB ON users (referrer_id)");
        $this->addSql("ALTER TABLE platforms RENAME COLUMN userid TO user_id");
        $this->addSql("ALTER TABLE platforms DROP CONSTRAINT fk_178186e364b64dcc");
        $this->addSql("DROP INDEX idx_178186e364b64dcc");
        $this->addSql("ALTER TABLE platforms ADD CONSTRAINT FK_178186E3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE INDEX IDX_178186E3A76ED395 ON platforms (user_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

//        $this->addSql("ALTER TABLE log_status_changes ADD fromstatus VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_status_changes ADD tostatus VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_status_changes DROP from_status");
//        $this->addSql("ALTER TABLE log_status_changes DROP to_status");

        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN from_status TO fromstatus");
        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN to_status TO tostatus");

        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN user_id TO userid");
        $this->addSql("ALTER TABLE log_status_changes RENAME COLUMN who_changed TO whochanged");

//        $this->addSql("ALTER TABLE users ADD billingyadengy VARCHAR(20) DEFAULT NULL");
//        $this->addSql("ALTER TABLE users ADD billingwmr VARCHAR(20) DEFAULT NULL");
//        $this->addSql("ALTER TABLE users ADD billingwmz VARCHAR(20) DEFAULT NULL");
//        $this->addSql("ALTER TABLE users DROP billing_ya_dengy");
//        $this->addSql("ALTER TABLE users DROP billing_wmr");
//        $this->addSql("ALTER TABLE users DROP billing_wmz");

        $this->addSql("ALTER TABLE users RENAME COLUMN billing_ya_dengy TO billingyadengy");
        $this->addSql("ALTER TABLE users RENAME COLUMN billing_wmr TO billingwmr");
        $this->addSql("ALTER TABLE users RENAME COLUMN billing_wmz TO billingwmz");

        $this->addSql("ALTER TABLE users RENAME COLUMN referrer_id TO referrerid");
        $this->addSql("ALTER TABLE users RENAME COLUMN registration_date TO registrationdate");
        $this->addSql("ALTER TABLE users RENAME COLUMN referral_value TO referralvalue");
        $this->addSql("ALTER TABLE users DROP CONSTRAINT FK_1483A5E9798C22DB");
        $this->addSql("DROP INDEX UNIQ_1483A5E975B9B9B9");
        $this->addSql("DROP INDEX IDX_1483A5E9798C22DB");
        $this->addSql("ALTER TABLE users ADD CONSTRAINT fk_1483a5e93dd9c621 FOREIGN KEY (referrerid) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE UNIQUE INDEX uniq_1483a5e992c172f9 ON users (referralvalue)");
        $this->addSql("CREATE INDEX idx_1483a5e93dd9c621 ON users (referrerid)");
        $this->addSql("ALTER TABLE platform_topics RENAME COLUMN trans_key TO transkey");
        $this->addSql("ALTER TABLE platforms RENAME COLUMN user_id TO userid");
        $this->addSql("ALTER TABLE platforms DROP CONSTRAINT FK_178186E3A76ED395");
        $this->addSql("DROP INDEX IDX_178186E3A76ED395");
        $this->addSql("ALTER TABLE platforms ADD CONSTRAINT fk_178186e364b64dcc FOREIGN KEY (userid) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE INDEX idx_178186e364b64dcc ON platforms (userid)");

//        $this->addSql("ALTER TABLE log_platform_status_changes ADD fromstatus VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_platform_status_changes ADD tostatus VARCHAR(100) NOT NULL");
//        $this->addSql("ALTER TABLE log_platform_status_changes DROP from_status");
//        $this->addSql("ALTER TABLE log_platform_status_changes DROP to_status");

        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN from_status TO fromstatus");
        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN to_status TO tostatus");

        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN platform_id TO platformid");
        $this->addSql("ALTER TABLE log_platform_status_changes RENAME COLUMN who_changed TO whochanged");
    }
}

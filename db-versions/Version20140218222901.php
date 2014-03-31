<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140218222901 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE supporter (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, event_id INT NOT NULL, details LONGTEXT NOT NULL, creation_time DATETIME NOT NULL, INDEX IDX_3F06E55979B1AD6 (company_id), INDEX IDX_3F06E5571F7E88B (event_id), UNIQUE INDEX supporter_index0 (company_id, event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE social_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, provider VARCHAR(20) NOT NULL, social_id VARCHAR(80) NOT NULL, username VARCHAR(80) NOT NULL, name VARCHAR(80) NOT NULL, email VARCHAR(160) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, is_default TINYINT(1) NOT NULL, INDEX IDX_E2C7F92A76ED395 (user_id), INDEX social_profile_index2 (is_default), UNIQUE INDEX social_profile_index0 (provider, user_id), UNIQUE INDEX social_profile_index1 (provider, social_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE registration_info (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, regular_price NUMERIC(13, 2) NOT NULL, early_price NUMERIC(13, 2) DEFAULT NULL, late_price NUMERIC(13, 2) DEFAULT NULL, student_label VARCHAR(45) DEFAULT NULL, student_rules LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_4B7268B71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, cost NUMERIC(13, 2) NOT NULL, status ENUM('0', '1', '2') NOT NULL, description VARCHAR(255) NOT NULL, creation_time DATETIME NOT NULL, last_update_time DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6D28840D77153098 (code), INDEX payment_index0 (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, email VARCHAR(160) NOT NULL, bio LONGTEXT DEFAULT NULL, creation_time DATETIME NOT NULL, admin TINYINT(1) DEFAULT '0' NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, name VARCHAR(80) NOT NULL, start DATE NOT NULL, end DATE NOT NULL, submissions_start DATETIME DEFAULT NULL, submissions_end DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA764D218E (location_id), INDEX event_index0 (start), INDEX event_index1 (submissions_start, submissions_end), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE evaluator (event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_750B980F71F7E88B (event_id), INDEX IDX_750B980FA76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE talk_evaluation (id INT AUTO_INCREMENT NOT NULL, talk_id INT DEFAULT NULL, evaluator_id INT DEFAULT NULL, originality_point INT NOT NULL, relevance_point INT NOT NULL, technical_quality_point INT NOT NULL, notes LONGTEXT DEFAULT NULL, creation_time DATETIME NOT NULL, admin_only TINYINT(1) DEFAULT '0' NOT NULL, INDEX IDX_9C479D356F0601D5 (talk_id), INDEX IDX_9C479D3543575BE2 (evaluator_id), UNIQUE INDEX talk_evaluation_index0 (evaluator_id, talk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, details LONGTEXT DEFAULT NULL, INDEX IDX_729F519B64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE discount_coupon (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(80) NOT NULL, discount NUMERIC(13, 2) NOT NULL, usage_limit INT NOT NULL, creation_time DATETIME NOT NULL, UNIQUE INDEX UNIQ_AFF133D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE talk_type (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(45) NOT NULL, length TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE scheduled_item (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, room_id INT DEFAULT NULL, talk_id INT DEFAULT NULL, `label` VARCHAR(80) NOT NULL, start_time DATETIME NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_D0DA623771F7E88B (event_id), INDEX IDX_D0DA623754177093 (room_id), UNIQUE INDEX UNIQ_D0DA62376F0601D5 (talk_id), UNIQUE INDEX scheduled_item_index0 (event_id, room_id, start_time), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, social_id VARCHAR(80) NOT NULL, name VARCHAR(80) NOT NULL, logo LONGBLOB NOT NULL, email VARCHAR(160) NOT NULL, phone VARCHAR(30) DEFAULT NULL, website VARCHAR(160) NOT NULL, twitter_id VARCHAR(80) DEFAULT NULL, fanpage VARCHAR(255) DEFAULT NULL, creation_time DATETIME NOT NULL, UNIQUE INDEX UNIQ_4FBF094FFFEB5B27 (social_id), UNIQUE INDEX UNIQ_4FBF094FE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE talk (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, type_id INT NOT NULL, title VARCHAR(120) NOT NULL, short_description LONGTEXT NOT NULL, long_description LONGTEXT NOT NULL, complexity ENUM('L','M','H') NOT NULL DEFAULT 'L', cost NUMERIC(13, 2) DEFAULT NULL, tags VARCHAR(120) DEFAULT NULL, approved TINYINT(1) DEFAULT NULL, creation_time DATETIME NOT NULL, INDEX IDX_9F24D5BB71F7E88B (event_id), INDEX IDX_9F24D5BBC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE speaker (talk_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7B85DB616F0601D5 (talk_id), INDEX IDX_7B85DB61A76ED395 (user_id), PRIMARY KEY(talk_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE attendee (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, user_id INT NOT NULL, coupon_id INT DEFAULT NULL, status ENUM('0', '1', '2', '3', '4') NOT NULL, arrived TINYINT(1) DEFAULT '0' NOT NULL, creation_time DATETIME NOT NULL, INDEX IDX_1150D56771F7E88B (event_id), INDEX IDX_1150D567A76ED395 (user_id), INDEX IDX_1150D56766C5951B (coupon_id), INDEX attendee_index0 (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE attendee_payment (attendee_id INT NOT NULL, payment_id INT NOT NULL, INDEX IDX_6BBB56F5BCFD782A (attendee_id), UNIQUE INDEX UNIQ_6BBB56F54C3A3BB (payment_id), PRIMARY KEY(attendee_id, payment_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, description LONGTEXT NOT NULL, longitude NUMERIC(13, 7) DEFAULT NULL, latitude NUMERIC(13, 7) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, talk_id INT NOT NULL, likes TINYINT(1) DEFAULT '1' NOT NULL, INDEX IDX_AB02B027A76ED395 (user_id), INDEX IDX_AB02B0276F0601D5 (talk_id), INDEX opinion_index1 (likes), UNIQUE INDEX opinion_index0 (user_id, talk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE supporter ADD CONSTRAINT FK_3F06E55979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)");
        $this->addSql("ALTER TABLE supporter ADD CONSTRAINT FK_3F06E5571F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE social_profile ADD CONSTRAINT FK_E2C7F92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE registration_info ADD CONSTRAINT FK_4B7268B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES location (id)");
        $this->addSql("ALTER TABLE evaluator ADD CONSTRAINT FK_750B980F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE evaluator ADD CONSTRAINT FK_750B980FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE talk_evaluation ADD CONSTRAINT FK_9C479D356F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id)");
        $this->addSql("ALTER TABLE talk_evaluation ADD CONSTRAINT FK_9C479D3543575BE2 FOREIGN KEY (evaluator_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE room ADD CONSTRAINT FK_729F519B64D218E FOREIGN KEY (location_id) REFERENCES location (id)");
        $this->addSql("ALTER TABLE scheduled_item ADD CONSTRAINT FK_D0DA623771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE scheduled_item ADD CONSTRAINT FK_D0DA623754177093 FOREIGN KEY (room_id) REFERENCES room (id)");
        $this->addSql("ALTER TABLE scheduled_item ADD CONSTRAINT FK_D0DA62376F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id)");
        $this->addSql("ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBC54C8C93 FOREIGN KEY (type_id) REFERENCES talk_type (id)");
        $this->addSql("ALTER TABLE speaker ADD CONSTRAINT FK_7B85DB616F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id)");
        $this->addSql("ALTER TABLE speaker ADD CONSTRAINT FK_7B85DB61A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE attendee ADD CONSTRAINT FK_1150D56771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE attendee ADD CONSTRAINT FK_1150D567A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE attendee ADD CONSTRAINT FK_1150D56766C5951B FOREIGN KEY (coupon_id) REFERENCES discount_coupon (id)");
        $this->addSql("ALTER TABLE attendee_payment ADD CONSTRAINT FK_6BBB56F5BCFD782A FOREIGN KEY (attendee_id) REFERENCES attendee (id)");
        $this->addSql("ALTER TABLE attendee_payment ADD CONSTRAINT FK_6BBB56F54C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)");
        $this->addSql("ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE opinion ADD CONSTRAINT FK_AB02B0276F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE attendee_payment DROP FOREIGN KEY FK_6BBB56F54C3A3BB");
        $this->addSql("ALTER TABLE social_profile DROP FOREIGN KEY FK_E2C7F92A76ED395");
        $this->addSql("ALTER TABLE evaluator DROP FOREIGN KEY FK_750B980FA76ED395");
        $this->addSql("ALTER TABLE talk_evaluation DROP FOREIGN KEY FK_9C479D3543575BE2");
        $this->addSql("ALTER TABLE speaker DROP FOREIGN KEY FK_7B85DB61A76ED395");
        $this->addSql("ALTER TABLE attendee DROP FOREIGN KEY FK_1150D567A76ED395");
        $this->addSql("ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027A76ED395");
        $this->addSql("ALTER TABLE supporter DROP FOREIGN KEY FK_3F06E5571F7E88B");
        $this->addSql("ALTER TABLE registration_info DROP FOREIGN KEY FK_4B7268B71F7E88B");
        $this->addSql("ALTER TABLE evaluator DROP FOREIGN KEY FK_750B980F71F7E88B");
        $this->addSql("ALTER TABLE scheduled_item DROP FOREIGN KEY FK_D0DA623771F7E88B");
        $this->addSql("ALTER TABLE talk DROP FOREIGN KEY FK_9F24D5BB71F7E88B");
        $this->addSql("ALTER TABLE attendee DROP FOREIGN KEY FK_1150D56771F7E88B");
        $this->addSql("ALTER TABLE scheduled_item DROP FOREIGN KEY FK_D0DA623754177093");
        $this->addSql("ALTER TABLE attendee DROP FOREIGN KEY FK_1150D56766C5951B");
        $this->addSql("ALTER TABLE talk DROP FOREIGN KEY FK_9F24D5BBC54C8C93");
        $this->addSql("ALTER TABLE supporter DROP FOREIGN KEY FK_3F06E55979B1AD6");
        $this->addSql("ALTER TABLE talk_evaluation DROP FOREIGN KEY FK_9C479D356F0601D5");
        $this->addSql("ALTER TABLE scheduled_item DROP FOREIGN KEY FK_D0DA62376F0601D5");
        $this->addSql("ALTER TABLE speaker DROP FOREIGN KEY FK_7B85DB616F0601D5");
        $this->addSql("ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B0276F0601D5");
        $this->addSql("ALTER TABLE attendee_payment DROP FOREIGN KEY FK_6BBB56F5BCFD782A");
        $this->addSql("ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA764D218E");
        $this->addSql("ALTER TABLE room DROP FOREIGN KEY FK_729F519B64D218E");
        $this->addSql("DROP TABLE supporter");
        $this->addSql("DROP TABLE social_profile");
        $this->addSql("DROP TABLE registration_info");
        $this->addSql("DROP TABLE payment");
        $this->addSql("DROP TABLE user");
        $this->addSql("DROP TABLE event");
        $this->addSql("DROP TABLE evaluator");
        $this->addSql("DROP TABLE talk_evaluation");
        $this->addSql("DROP TABLE room");
        $this->addSql("DROP TABLE discount_coupon");
        $this->addSql("DROP TABLE talk_type");
        $this->addSql("DROP TABLE scheduled_item");
        $this->addSql("DROP TABLE company");
        $this->addSql("DROP TABLE talk");
        $this->addSql("DROP TABLE speaker");
        $this->addSql("DROP TABLE attendee");
        $this->addSql("DROP TABLE attendee_payment");
        $this->addSql("DROP TABLE location");
        $this->addSql("DROP TABLE opinion");
    }
}

<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140504192942 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE registration_info ADD talks_price NUMERIC(13, 2) NOT NULL, ADD cost_variation NUMERIC(13, 2) NOT NULL, DROP early_price, DROP late_price, CHANGE regular_price workshops_price NUMERIC(13, 2) NOT NULL");
        $this->addSql("ALTER TABLE registration_info MODIFY student_discount DECIMAL(13,2) NULL AFTER cost_variation");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE registration_info ADD regular_price NUMERIC(13, 2) NOT NULL, ADD early_price NUMERIC(13, 2) DEFAULT NULL, ADD late_price NUMERIC(13, 2) DEFAULT NULL, DROP workshops_price, DROP talks_price, DROP cost_variation");
    }
}

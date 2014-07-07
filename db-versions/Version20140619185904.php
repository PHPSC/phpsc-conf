<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140619185904 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE scheduled_item DROP FOREIGN KEY FK_D0DA62376F0601D5;");
        $this->addSql("ALTER TABLE scheduled_item DROP INDEX UNIQ_D0DA62376F0601D5");
        $this->addSql("ALTER TABLE scheduled_item ADD INDEX IDX_D0DA62376F0601D5 (talk_id), ADD CONSTRAINT FK_D0DA62376F0601D5 FOREIGN KEY (`talk_id`) REFERENCES `talk` (`id`)");
        $this->addSql("ALTER TABLE scheduled_item CHANGE `label` `label` VARCHAR(120) NOT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE scheduled_item DROP FOREIGN KEY FK_D0DA62376F0601D5;");
        $this->addSql("ALTER TABLE scheduled_item DROP INDEX IDX_D0DA62376F0601D5");
        $this->addSql("ALTER TABLE scheduled_item ADD INDEX UNIQ_D0DA62376F0601D5 (talk_id), ADD CONSTRAINT FK_D0DA62376F0601D5 FOREIGN KEY (`talk_id`) REFERENCES `talk` (`id`)");
        $this->addSql("ALTER TABLE scheduled_item CHANGE `label` `label` VARCHAR(80) NOT NULL");
    }
}

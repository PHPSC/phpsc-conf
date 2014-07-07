<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140706212049 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, event_id INT NOT NULL, quota_id INT NOT NULL, creation_time DATETIME NOT NULL, INDEX IDX_818CC9D4979B1AD6 (company_id), INDEX IDX_818CC9D471F7E88B (event_id), INDEX IDX_818CC9D454E2C62F (quota_id), UNIQUE INDEX sponsor_index0 (company_id, event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)");
        $this->addSql("ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D454E2C62F FOREIGN KEY (quota_id) REFERENCES sponsorship_quota (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE sponsor");
    }
}

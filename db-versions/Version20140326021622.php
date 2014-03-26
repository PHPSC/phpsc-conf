<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140326021622 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE event ADD logo_id INT DEFAULT NULL AFTER location_id");
        $this->addSql("ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F98F144A FOREIGN KEY (logo_id) REFERENCES logo (id)");
        $this->addSql("CREATE INDEX IDX_3BAE0AA7F98F144A ON event (logo_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F98F144A");
        $this->addSql("DROP INDEX IDX_3BAE0AA7F98F144A ON event");
        $this->addSql("ALTER TABLE event DROP logo_id");
    }
}

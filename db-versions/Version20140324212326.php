<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140324212326 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE location ADD logo_id INT");
        $this->addSql("ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBF98F144A FOREIGN KEY (logo_id) REFERENCES logo (id)");
        $this->addSql("CREATE INDEX IDX_5E9E89CBF98F144A ON location (logo_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBF98F144A");
        $this->addSql("DROP INDEX IDX_5E9E89CBF98F144A ON location");
        $this->addSql("ALTER TABLE location DROP logo_id");
    }
}

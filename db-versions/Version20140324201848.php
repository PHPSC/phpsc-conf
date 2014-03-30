<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140324201848 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE company ADD logo_id INT");
        $this->addSql("ALTER TABLE company ADD CONSTRAINT FK_4FBF094FF98F144A FOREIGN KEY (logo_id) REFERENCES logo (id)");
        $this->addSql("CREATE INDEX IDX_4FBF094FF98F144A ON company (logo_id)");
        $this->addSql("INSERT INTO logo (id, image, created_at) SELECT id, logo, NOW() FROM company");
        $this->addSql("UPDATE company SET logo_id = id");
        $this->addSql("ALTER TABLE company DROP logo");
        $this->addSql("ALTER TABLE company MODIFY logo_id INT NOT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FF98F144A");
        $this->addSql("DROP INDEX IDX_4FBF094FF98F144A ON company");
        $this->addSql("ALTER TABLE company ADD logo LONGBLOB NOT NULL");
        $this->addSql("UPDATE company, logo SET company.logo = logo.image WHERE company.logo_id = logo.id");
        $this->addSql("ALTER TABLE company DROP logo_id");
    }
}

<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\Query;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140310201053 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE talk CHANGE tags tags TINYTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)'");
        $this->changeTags();
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE talk CHANGE tags tags VARCHAR(120) DEFAULT NULL");
        $this->changeTags(false);
    }

    protected function changeTags($up = true)
    {
        $select = $this->connection->query('SELECT id, tags FROM talk WHERE tags IS NOT NULL');
        $update = $this->connection->prepare('UPDATE talk SET tags = :tags WHERE id = :id');

        $select->execute();

        foreach ($select->fetchAll(Query::HYDRATE_SIMPLEOBJECT) as $item) {
            $update->execute(
                [
                    'id' => $item->id,
                    'tags' => $up ? json_encode(explode(',', $item->tags)) : implode(',', json_decode($item->tags))
                ]
            );
        }
    }
}

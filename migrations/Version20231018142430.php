<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018142430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__publication AS SELECT id, message, date_publication FROM publication');
        $this->addSql('DROP TABLE publication');
        $this->addSql('CREATE TABLE publication (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, auteur_id INTEGER NOT NULL, message CLOB NOT NULL, date_publication DATETIME NOT NULL, CONSTRAINT FK_AF3C677960BB6FE6 FOREIGN KEY (auteur_id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO publication (id, message, date_publication) SELECT id, message, date_publication FROM __temp__publication');
        $this->addSql('DROP TABLE __temp__publication');
        $this->addSql('CREATE INDEX IDX_AF3C677960BB6FE6 ON publication (auteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__publication AS SELECT id, message, date_publication FROM publication');
        $this->addSql('DROP TABLE publication');
        $this->addSql('CREATE TABLE publication (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, message CLOB NOT NULL, date_publication DATETIME NOT NULL)');
        $this->addSql('INSERT INTO publication (id, message, date_publication) SELECT id, message, date_publication FROM __temp__publication');
        $this->addSql('DROP TABLE __temp__publication');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628102307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE users');
        $this->addSql('DROP INDEX IDX_BFDD3168A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles AS SELECT id, user_id, title, content, created_at, link FROM articles');
        $this->addSql('DROP TABLE articles');
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, link VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_BFDD3168A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO articles (id, user_id, title, content, created_at, link) SELECT id, user_id, title, content, created_at, link FROM __temp__articles');
        $this->addSql('DROP TABLE __temp__articles');
        $this->addSql('CREATE INDEX IDX_BFDD3168A76ED395 ON articles (user_id)');
        $this->addSql('DROP INDEX IDX_5F9E962AA76ED395');
        $this->addSql('DROP INDEX IDX_5F9E962A7294869C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, article_id, user_id, content, added_at FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content CLOB NOT NULL COLLATE BINARY, added_at DATETIME NOT NULL, CONSTRAINT FK_5F9E962A7294869C FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comments (id, article_id, user_id, content, added_at) SELECT id, article_id, user_id, content, added_at FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A7294869C ON comments (article_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN role VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, admin BOOLEAN DEFAULT NULL)');
        $this->addSql('DROP INDEX IDX_BFDD3168A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles AS SELECT id, user_id, title, content, created_at, link FROM articles');
        $this->addSql('DROP TABLE articles');
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL, link VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO articles (id, user_id, title, content, created_at, link) SELECT id, user_id, title, content, created_at, link FROM __temp__articles');
        $this->addSql('DROP TABLE __temp__articles');
        $this->addSql('CREATE INDEX IDX_BFDD3168A76ED395 ON articles (user_id)');
        $this->addSql('DROP INDEX IDX_5F9E962A7294869C');
        $this->addSql('DROP INDEX IDX_5F9E962AA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, article_id, user_id, content, added_at FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content CLOB NOT NULL, added_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO comments (id, article_id, user_id, content, added_at) SELECT id, article_id, user_id, content, added_at FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962A7294869C ON comments (article_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, login, password, email FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(15) NOT NULL, password VARCHAR(20) NOT NULL, email VARCHAR(125) NOT NULL)');
        $this->addSql('INSERT INTO user (id, login, password, email) SELECT id, login, password, email FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
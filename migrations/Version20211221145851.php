<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221145851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD paragraph1 VARCHAR(255) DEFAULT NULL, ADD paragraph2 VARCHAR(255) DEFAULT NULL, ADD paragraph3 VARCHAR(255) DEFAULT NULL, ADD paragraph4 VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE foto2 foto2 VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP paragraph1, DROP paragraph2, DROP paragraph3, DROP paragraph4, DROP created_at, CHANGE foto2 foto2 VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product DROP create_at');
    }
}

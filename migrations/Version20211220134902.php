<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211220134902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD paragraph1 VARCHAR(255) DEFAULT NULL, ADD paragraph2 VARCHAR(255) DEFAULT NULL, ADD paragraph3 VARCHAR(255) DEFAULT NULL, ADD paragraph4 VARCHAR(255) DEFAULT NULL, CHANGE foto1 foto1 VARCHAR(100) DEFAULT NULL, CHANGE foto2 foto2 VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP paragraph1, DROP paragraph2, DROP paragraph3, DROP paragraph4, CHANGE foto1 foto1 VARCHAR(300) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE foto2 foto2 VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

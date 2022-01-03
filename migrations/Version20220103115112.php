<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103115112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achievements (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, paragraph VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, text VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE our_mission CHANGE img img VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE achievements');
        $this->addSql('ALTER TABLE our_mission CHANGE img img VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'fon-foto-default-3.jpeg\' COLLATE `utf8mb4_unicode_ci`');
    }
}

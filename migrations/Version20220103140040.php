<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103140040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE press (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, text VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achievements CHANGE img img VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE our_mission CHANGE img img VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE press');
        $this->addSql('ALTER TABLE achievements CHANGE img img VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'fon-foto-default-3.jpeg\' NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE our_mission CHANGE img img VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'fon-foto-default-3.jpeg\' COLLATE `utf8mb4_unicode_ci`');
    }
}

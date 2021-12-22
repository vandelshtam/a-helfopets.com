<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222074330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP updated_at, CHANGE comment_foto2 comment_foto2 VARCHAR(2550) DEFAULT NULL, CHANGE paragraph2 paragraph2 VARCHAR(2550) DEFAULT NULL, CHANGE paragraph3 paragraph3 VARCHAR(2550) DEFAULT NULL, CHANGE paragraph4 paragraph4 VARCHAR(2550) DEFAULT NULL, CHANGE paragraph1 paragraph1 VARCHAR(2550) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE product DROP updated_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE service ADD name VARCHAR(255) DEFAULT NULL, ADD discription VARCHAR(255) DEFAULT NULL, ADD avatar VARCHAR(255) DEFAULT NULL, DROP название, DROP описание, DROP аватар');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE comment_foto2 comment_foto2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE paragraph1 paragraph1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE paragraph2 paragraph2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE paragraph3 paragraph3 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE paragraph4 paragraph4 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE product ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE service ADD название VARCHAR(256) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ADD описание TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ADD аватар VARCHAR(128) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, DROP name, DROP discription, DROP avatar');
    }
}

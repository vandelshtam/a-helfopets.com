<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120071303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achievement (id INT AUTO_INCREMENT NOT NULL, document1 VARCHAR(255) DEFAULT NULL, document2 VARCHAR(255) DEFAULT NULL, document3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A253E2E969B');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A253E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('ALTER TABLE fotoblog DROP FOREIGN KEY fotoblog_ibfk_1');
        $this->addSql('ALTER TABLE fotoblog ADD CONSTRAINT FK_64D86B4DDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE fotoreview DROP FOREIGN KEY FK_21CCD1373E2E969B');
        $this->addSql('ALTER TABLE fotoreview ADD CONSTRAINT FK_21CCD1373E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE achievement');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A253E2E969B');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A253E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fotoblog DROP FOREIGN KEY FK_64D86B4DDAE07E97');
        $this->addSql('ALTER TABLE fotoblog ADD CONSTRAINT fotoblog_ibfk_1 FOREIGN KEY (blog_id) REFERENCES blog (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fotoreview DROP FOREIGN KEY FK_21CCD1373E2E969B');
        $this->addSql('ALTER TABLE fotoreview ADD CONSTRAINT FK_21CCD1373E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}

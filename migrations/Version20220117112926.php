<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117112926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fotoblog DROP FOREIGN KEY FK_64D86B4DDAE07E97');
        $this->addSql('ALTER TABLE fotoblog ADD CONSTRAINT FK_64D86B4DDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE ratingblog DROP FOREIGN KEY FK_94A77540DAE07E97');
        $this->addSql('ALTER TABLE ratingblog ADD CONSTRAINT FK_94A77540DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE review ADD banned VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fotoblog DROP FOREIGN KEY FK_64D86B4DDAE07E97');
        $this->addSql('ALTER TABLE fotoblog ADD CONSTRAINT FK_64D86B4DDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ratingblog DROP FOREIGN KEY FK_94A77540DAE07E97');
        $this->addSql('ALTER TABLE ratingblog ADD CONSTRAINT FK_94A77540DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review DROP banned');
    }
}

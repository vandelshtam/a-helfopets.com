<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120155656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76BDC78EA7');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76BDC78EA7 FOREIGN KEY (achievements_id) REFERENCES achievements (id)');
        $this->addSql('ALTER TABLE press ADD sources VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76BDC78EA7');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76BDC78EA7 FOREIGN KEY (achievements_id) REFERENCES achievements (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE press DROP sources');
    }
}

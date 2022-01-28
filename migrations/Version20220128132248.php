<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128132248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achievements (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, paragraph VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, text VARCHAR(1000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE answer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, review_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, answer_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_DADD4A253E2E969B ON answer (review_id)');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title CLOB DEFAULT NULL, comment_foto CLOB DEFAULT NULL, article CLOB DEFAULT NULL, comment_auxiliary_one CLOB DEFAULT NULL, author CLOB NOT NULL, preview CLOB DEFAULT NULL, avatar_article VARCHAR(255) DEFAULT NULL, foto1 CLOB DEFAULT NULL, foto2 VARCHAR(100) DEFAULT NULL, comment_foto2 VARCHAR(2550) DEFAULT NULL, paragraph1 CLOB DEFAULT NULL, paragraph2 CLOB DEFAULT NULL, paragraph3 CLOB DEFAULT NULL, paragraph4 CLOB DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE blog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, preview VARCHAR(755) DEFAULT NULL, description VARCHAR(755) NOT NULL, text VARCHAR(2755) DEFAULT NULL, text2 VARCHAR(2755) DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, foto VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , linltitle VARCHAR(755) DEFAULT NULL, link VARCHAR(755) DEFAULT NULL, titleslider VARCHAR(755) DEFAULT NULL, descriptionslider VARCHAR(755) DEFAULT NULL, linkslider VARCHAR(755) DEFAULT NULL)');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, foto VARCHAR(255) DEFAULT NULL, comment CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE consultation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, phone INTEGER DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, category VARCHAR(50) DEFAULT NULL, message VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE document (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, achievements_id INTEGER DEFAULT NULL, document1 VARCHAR(255) DEFAULT NULL, document2 VARCHAR(255) DEFAULT NULL, document3 VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_D8698A76BDC78EA7 ON document (achievements_id)');
        $this->addSql('CREATE TABLE fast_consultation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, phone VARCHAR(16) DEFAULT NULL)');
        $this->addSql('CREATE TABLE fotoblog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, blog_id INTEGER DEFAULT NULL, foto VARCHAR(255) DEFAULT NULL, title VARCHAR(755) DEFAULT NULL, description VARCHAR(2255) DEFAULT NULL, link VARCHAR(555) DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_64D86B4DDAE07E97 ON fotoblog (blog_id)');
        $this->addSql('CREATE TABLE fotoreview (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, review_id INTEGER DEFAULT NULL, foto VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_21CCD1373E2E969B ON fotoreview (review_id)');
        $this->addSql('CREATE TABLE our_mission (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, text VARCHAR(750) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE press (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, text VARCHAR(1000) DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , sources VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE rating (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, grade INTEGER NOT NULL, ip VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE ratingblog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, blog_id INTEGER DEFAULT NULL, rating INTEGER DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_94A77540DAE07E97 ON ratingblog (blog_id)');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, text VARCHAR(255) NOT NULL, answer_id INTEGER DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , ip VARCHAR(255) DEFAULT NULL, banned INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE service (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name CLOB DEFAULT NULL, discription CLOB DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, description2 CLOB DEFAULT NULL, description3 CLOB DEFAULT NULL, document VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE service_category (service_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(service_id, category_id))');
        $this->addSql('CREATE INDEX IDX_FF3A42FCED5CA9E6 ON service_category (service_id)');
        $this->addSql('CREATE INDEX IDX_FF3A42FC12469DE2 ON service_category (category_id)');
        $this->addSql('CREATE TABLE slider (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service_name VARCHAR(255) DEFAULT NULL, discription_service VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE achievements');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE fast_consultation');
        $this->addSql('DROP TABLE fotoblog');
        $this->addSql('DROP TABLE fotoreview');
        $this->addSql('DROP TABLE our_mission');
        $this->addSql('DROP TABLE press');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE ratingblog');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_category');
        $this->addSql('DROP TABLE slider');
        $this->addSql('DROP TABLE user');
    }
}

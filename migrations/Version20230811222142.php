<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230811222142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Migration pour l'ajout de nouvelles tables relatives aux administrateurs, articles, espaces, utilisateurs et autres fonctionnalités essentielles du système.";
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresses (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(50) NOT NULL, city VARCHAR(255) NOT NULL, street_number VARCHAR(20) NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_content (id INT AUTO_INCREMENT NOT NULL, content_type VARCHAR(20) NOT NULL, content_order INT NOT NULL, language VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_log (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(20) NOT NULL, action_date DATETIME NOT NULL, reason_for_deletion LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(20) NOT NULL, image VARCHAR(50) DEFAULT NULL, creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversations (id INT AUTO_INCREMENT NOT NULL, start_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, added_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, sent_time DATETIME NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, reference_id INT NOT NULL, message VARCHAR(255) NOT NULL, date_sent DATETIME NOT NULL, was_read TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, payment_date DATETIME NOT NULL, status VARCHAR(20) NOT NULL, method VARCHAR(20) NOT NULL, stripe_charge_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reports (id INT AUTO_INCREMENT NOT NULL, report_date DATETIME NOT NULL, report_type VARCHAR(20) NOT NULL, reason LONGTEXT DEFAULT NULL, report_status VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, price NUMERIC(10, 2) NOT NULL, reservation_date DATETIME NOT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_equipement_link (id INT AUTO_INCREMENT NOT NULL, quantity INT DEFAULT NULL, equipped TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_equipements (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_images (id INT AUTO_INCREMENT NOT NULL, image_path VARCHAR(50) NOT NULL, image_description VARCHAR(255) DEFAULT NULL, upload_date DATETIME NOT NULL, sort_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_reviews (id INT AUTO_INCREMENT NOT NULL, review_date DATETIME NOT NULL, rating INT DEFAULT NULL, comment LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_translations (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(20) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spaces (id INT AUTO_INCREMENT NOT NULL, price NUMERIC(10, 2) NOT NULL, surface INT NOT NULL, entry_width INT DEFAULT NULL, entry_length INT DEFAULT NULL, floor_position VARCHAR(50) NOT NULL, `condition` VARCHAR(50) DEFAULT NULL, registration_date DATETIME NOT NULL, availability_start DATETIME NOT NULL, availability_end DATETIME NOT NULL, status VARCHAR(50) NOT NULL, is_published TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_consent (id INT AUTO_INCREMENT NOT NULL, consent_type VARCHAR(20) NOT NULL, consent_given TINYINT(1) NOT NULL, consent_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE adresses');
        $this->addSql('DROP TABLE article_content');
        $this->addSql('DROP TABLE article_log');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE conversations');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE reports');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE space_categories');
        $this->addSql('DROP TABLE space_equipement_link');
        $this->addSql('DROP TABLE space_equipements');
        $this->addSql('DROP TABLE space_images');
        $this->addSql('DROP TABLE space_reviews');
        $this->addSql('DROP TABLE space_translations');
        $this->addSql('DROP TABLE spaces');
        $this->addSql('DROP TABLE user_consent');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

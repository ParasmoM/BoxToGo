<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230908161640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Révision complète de la base de données, les entités de base ont été recréées.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64789090866B');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B32A76ED395');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FA76ED395');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64781FB8D185');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B6478A76ED395');
        $this->addSql('ALTER TABLE user_consent DROP FOREIGN KEY FK_3B1F161AA76ED395');
        $this->addSql('CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, street_number VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversations (id INT AUTO_INCREMENT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_spaces (id INT AUTO_INCREMENT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', image_path VARCHAR(255) NOT NULL, sort_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_amenities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_amenity_links (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', given_name VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, birth_date DATETIME DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, gender VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, language VARCHAR(255) NOT NULL, appearance VARCHAR(255) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY FK_EF19255223575340');
        $this->addSql('ALTER TABLE article_content DROP FOREIGN KEY FK_1317741E7294869C');
        $this->addSql('ALTER TABLE article_log DROP FOREIGN KEY FK_1D204BBF7294869C');
        $this->addSql('ALTER TABLE article_log DROP FOREIGN KEY FK_1D204BBF642B8210');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168642B8210');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43223575340');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA745642B8210');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA7451FB8D185');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA745A76ED395');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA74523575340');
        $this->addSql('ALTER TABLE space_equipement_link DROP FOREIGN KEY FK_A36D7E3923575340');
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements DROP FOREIGN KEY FK_A68F315FCF445211');
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements DROP FOREIGN KEY FK_A68F315FF2089F7E');
        $this->addSql('ALTER TABLE space_images DROP FOREIGN KEY FK_91A33DFF23575340');
        $this->addSql('ALTER TABLE talks DROP FOREIGN KEY FK_472281DACD53EDB6');
        $this->addSql('ALTER TABLE talks DROP FOREIGN KEY FK_472281DAF624B39D');
        $this->addSql('ALTER TABLE talks DROP FOREIGN KEY FK_472281DA23575340');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E93DA5256D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E984A0A3ED');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E94DE7DC5C');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9642B8210');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE adresses');
        $this->addSql('DROP TABLE article_content');
        $this->addSql('DROP TABLE article_log');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE reports');
        $this->addSql('DROP TABLE space_categories');
        $this->addSql('DROP TABLE space_equipement_link');
        $this->addSql('DROP TABLE space_equipement_link_space_equipements');
        $this->addSql('DROP TABLE space_equipements');
        $this->addSql('DROP TABLE space_images');
        $this->addSql('DROP TABLE talks');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE contents CHANGE title_nl title_ne VARCHAR(255) DEFAULT NULL, CHANGE description_nl description_ne LONGTEXT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_65D29B32A76ED395 ON payments');
        $this->addSql('ALTER TABLE payments ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD stripe_id VARCHAR(255) NOT NULL, DROP user_id, DROP amount, DROP stripe_brand, DROP stripe_last4, DROP created_at, CHANGE reference reference VARCHAR(255) NOT NULL, CHANGE method method VARCHAR(255) NOT NULL, CHANGE stripe_token stripe_token VARCHAR(255) NOT NULL, CHANGE stripe_status stripe_status VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE stripe_charge_id price VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2394C3A3BB');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23923575340');
        $this->addSql('DROP INDEX IDX_4DA239A76ED395 ON reservations');
        $this->addSql('DROP INDEX UNIQ_4DA2394C3A3BB ON reservations');
        $this->addSql('DROP INDEX IDX_4DA23923575340 ON reservations');
        $this->addSql('ALTER TABLE reservations ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP user_id, DROP space_id, DROP payment_id, DROP stripe_token, DROP brand_stripe, DROP last4_stripe, DROP id_charge_stripe, DROP status_stripe, DROP created_at, CHANGE price price VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE reference reference VARCHAR(255) NOT NULL, CHANGE date_start date_start DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', CHANGE date_end date_end DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F3E2E969B');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F23575340');
        $this->addSql('DROP INDEX IDX_6970EB0F23575340 ON reviews');
        $this->addSql('DROP INDEX IDX_6970EB0F3E2E969B ON reviews');
        $this->addSql('DROP INDEX IDX_6970EB0FA76ED395 ON reviews');
        $this->addSql('ALTER TABLE reviews ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP user_id, DROP space_id, DROP review_id, DROP review_date');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B647884A0A3ED');
        $this->addSql('DROP INDEX UNIQ_DD2B647884A0A3ED ON spaces');
        $this->addSql('DROP INDEX IDX_DD2B64781FB8D185 ON spaces');
        $this->addSql('DROP INDEX IDX_DD2B6478A76ED395 ON spaces');
        $this->addSql('DROP INDEX IDX_DD2B64789090866B ON spaces');
        $this->addSql('ALTER TABLE spaces ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD floor_level VARCHAR(255) DEFAULT NULL, ADD condition_status VARCHAR(255) DEFAULT NULL, ADD availability_start_date DATETIME DEFAULT NULL, ADD availability_end_date DATETIME DEFAULT NULL, DROP user_id, DROP host_id, DROP space_categ_id, DROP content_id, DROP floor_position, DROP item_condition, DROP registration_date, DROP availability_start, DROP availability_end, CHANGE status status VARCHAR(255) NOT NULL, CHANGE is_published is_published TINYINT(1) DEFAULT NULL, CHANGE reference reference VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_3B1F161AA76ED395 ON user_consent');
        $this->addSql('ALTER TABLE user_consent ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP user_id, DROP consent_date, CHANGE consent_type consent_type VARCHAR(255) DEFAULT NULL, CHANGE consent_given consent_given TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE adresses (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, country VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, city VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, street_number VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, street VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, postal_code VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_EF19255223575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE article_content (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, content_type VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content_order INT NOT NULL, language VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1317741E7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE article_log (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, article_id INT DEFAULT NULL, action VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, action_date DATETIME NOT NULL, reason_for_deletion LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1D204BBF642B8210 (admin_id), INDEX IDX_1D204BBF7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, title VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, creation_date DATETIME NOT NULL, INDEX IDX_BFDD3168642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, space_id INT DEFAULT NULL, added_date DATETIME NOT NULL, INDEX IDX_8933C432A76ED395 (user_id), INDEX IDX_8933C43223575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX IDX_DB021E96A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, reference_id INT NOT NULL, message VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_sent DATETIME NOT NULL, was_read TINYINT(1) NOT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reports (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, host_id INT DEFAULT NULL, space_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, report_date DATETIME NOT NULL, report_type VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, reason LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, report_status VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_F11FA745A76ED395 (user_id), INDEX IDX_F11FA74523575340 (space_id), INDEX IDX_F11FA7451FB8D185 (host_id), INDEX IDX_F11FA745642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE space_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE space_equipement_link (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, INDEX IDX_A36D7E3923575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE space_equipement_link_space_equipements (space_equipement_link_id INT NOT NULL, space_equipements_id INT NOT NULL, INDEX IDX_A68F315FF2089F7E (space_equipements_id), INDEX IDX_A68F315FCF445211 (space_equipement_link_id), PRIMARY KEY(space_equipement_link_id, space_equipements_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE space_equipements (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE space_images (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, image_path VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image_description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, upload_date DATETIME NOT NULL, sort_order INT NOT NULL, INDEX IDX_91A33DFF23575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE talks (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, space_id INT DEFAULT NULL, created_at DATETIME NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_472281DACD53EDB6 (receiver_id), INDEX IDX_472281DAF624B39D (sender_id), INDEX IDX_472281DA23575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, adresse_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, content_id INT DEFAULT NULL, image_id INT DEFAULT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, given_name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, family_name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, birth_date DATETIME NOT NULL, profile_picture VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, registration_date DATETIME NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, gender VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, phone_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, language VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, google_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_verified TINYINT(1) NOT NULL, preference LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', appearance VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_1483A5E94DE7DC5C (adresse_id), UNIQUE INDEX UNIQ_1483A5E984A0A3ED (content_id), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9642B8210 (admin_id), UNIQUE INDEX UNIQ_1483A5E93DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT FK_EF19255223575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE article_content ADD CONSTRAINT FK_1317741E7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE article_log ADD CONSTRAINT FK_1D204BBF7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE article_log ADD CONSTRAINT FK_1D204BBF642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43223575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA745642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA7451FB8D185 FOREIGN KEY (host_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA745A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA74523575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE space_equipement_link ADD CONSTRAINT FK_A36D7E3923575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements ADD CONSTRAINT FK_A68F315FCF445211 FOREIGN KEY (space_equipement_link_id) REFERENCES space_equipement_link (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements ADD CONSTRAINT FK_A68F315FF2089F7E FOREIGN KEY (space_equipements_id) REFERENCES space_equipements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE space_images ADD CONSTRAINT FK_91A33DFF23575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE talks ADD CONSTRAINT FK_472281DACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE talks ADD CONSTRAINT FK_472281DAF624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE talks ADD CONSTRAINT FK_472281DA23575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E93DA5256D FOREIGN KEY (image_id) REFERENCES space_images (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E984A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E94DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresses (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE conversations');
        $this->addSql('DROP TABLE favorite_spaces');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE space_amenities');
        $this->addSql('DROP TABLE space_amenity_links');
        $this->addSql('DROP TABLE space_types');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE contents CHANGE title_ne title_nl VARCHAR(255) DEFAULT NULL, CHANGE description_ne description_nl LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD user_id INT DEFAULT NULL, ADD amount NUMERIC(10, 2) NOT NULL, ADD stripe_charge_id VARCHAR(255) NOT NULL, ADD stripe_brand VARCHAR(255) DEFAULT NULL, ADD stripe_last4 VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL, DROP create_at, DROP price, DROP stripe_id, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE method method VARCHAR(20) NOT NULL, CHANGE stripe_token stripe_token VARCHAR(255) DEFAULT NULL, CHANGE stripe_status stripe_status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_65D29B32A76ED395 ON payments (user_id)');
        $this->addSql('ALTER TABLE reservations ADD user_id INT DEFAULT NULL, ADD space_id INT DEFAULT NULL, ADD payment_id INT DEFAULT NULL, ADD stripe_token VARCHAR(255) DEFAULT NULL, ADD brand_stripe VARCHAR(255) DEFAULT NULL, ADD last4_stripe VARCHAR(255) DEFAULT NULL, ADD id_charge_stripe VARCHAR(255) DEFAULT NULL, ADD status_stripe VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL, DROP create_at, CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(20) NOT NULL, CHANGE price price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2394C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23923575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_4DA239A76ED395 ON reservations (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DA2394C3A3BB ON reservations (payment_id)');
        $this->addSql('CREATE INDEX IDX_4DA23923575340 ON reservations (space_id)');
        $this->addSql('ALTER TABLE reviews ADD user_id INT DEFAULT NULL, ADD space_id INT DEFAULT NULL, ADD review_id INT DEFAULT NULL, ADD review_date DATETIME NOT NULL, DROP create_at');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F3E2E969B FOREIGN KEY (review_id) REFERENCES reviews (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F23575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_6970EB0F23575340 ON reviews (space_id)');
        $this->addSql('CREATE INDEX IDX_6970EB0F3E2E969B ON reviews (review_id)');
        $this->addSql('CREATE INDEX IDX_6970EB0FA76ED395 ON reviews (user_id)');
        $this->addSql('ALTER TABLE spaces ADD user_id INT DEFAULT NULL, ADD host_id INT DEFAULT NULL, ADD space_categ_id INT DEFAULT NULL, ADD content_id INT DEFAULT NULL, ADD floor_position VARCHAR(50) NOT NULL, ADD item_condition VARCHAR(50) DEFAULT NULL, ADD registration_date DATETIME NOT NULL, ADD availability_start DATETIME DEFAULT NULL, ADD availability_end DATETIME DEFAULT NULL, DROP create_at, DROP floor_level, DROP condition_status, DROP availability_start_date, DROP availability_end_date, CHANGE status status VARCHAR(50) NOT NULL, CHANGE is_published is_published TINYINT(1) NOT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64789090866B FOREIGN KEY (space_categ_id) REFERENCES space_categories (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64781FB8D185 FOREIGN KEY (host_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B6478A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B647884A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD2B647884A0A3ED ON spaces (content_id)');
        $this->addSql('CREATE INDEX IDX_DD2B64781FB8D185 ON spaces (host_id)');
        $this->addSql('CREATE INDEX IDX_DD2B6478A76ED395 ON spaces (user_id)');
        $this->addSql('CREATE INDEX IDX_DD2B64789090866B ON spaces (space_categ_id)');
        $this->addSql('ALTER TABLE user_consent ADD user_id INT DEFAULT NULL, ADD consent_date DATETIME NOT NULL, DROP create_at, CHANGE consent_type consent_type VARCHAR(20) NOT NULL, CHANGE consent_given consent_given TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user_consent ADD CONSTRAINT FK_3B1F161AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_3B1F161AA76ED395 ON user_consent (user_id)');
    }
}

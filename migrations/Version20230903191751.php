<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903191751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contents (id INT AUTO_INCREMENT NOT NULL, title_fr VARCHAR(255) DEFAULT NULL, title_en VARCHAR(255) DEFAULT NULL, title_nl VARCHAR(255) DEFAULT NULL, description_fr LONGTEXT DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, description_nl LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, space_id INT DEFAULT NULL, review_id INT DEFAULT NULL, review_date DATETIME NOT NULL, rating INT DEFAULT NULL, comment LONGTEXT NOT NULL, INDEX IDX_6970EB0FA76ED395 (user_id), INDEX IDX_6970EB0F23575340 (space_id), INDEX IDX_6970EB0F3E2E969B (review_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F23575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F3E2E969B FOREIGN KEY (review_id) REFERENCES reviews (id)');
        $this->addSql('ALTER TABLE space_reviews DROP FOREIGN KEY FK_E9643047A76ED395');
        $this->addSql('ALTER TABLE space_translations DROP FOREIGN KEY FK_7FCD647023575340');
        $this->addSql('DROP TABLE space_reviews');
        $this->addSql('DROP TABLE space_translations');
        $this->addSql('ALTER TABLE payments ADD reference VARCHAR(255) DEFAULT NULL, ADD stripe_token VARCHAR(255) DEFAULT NULL, ADD stripe_brand VARCHAR(255) DEFAULT NULL, ADD stripe_last4 VARCHAR(255) DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, CHANGE status stripe_status VARCHAR(20) NOT NULL, CHANGE payment_date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservations ADD reference VARCHAR(255) DEFAULT NULL, ADD stripe_token VARCHAR(255) DEFAULT NULL, ADD brand_stripe VARCHAR(255) DEFAULT NULL, ADD last4_stripe VARCHAR(255) DEFAULT NULL, ADD id_charge_stripe VARCHAR(255) DEFAULT NULL, ADD status_stripe VARCHAR(255) DEFAULT NULL, CHANGE reservation_date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE space_equipements DROP quantity, DROP equipped');
        $this->addSql('ALTER TABLE space_images CHANGE image_path image_path VARCHAR(250) NOT NULL');
        $this->addSql('ALTER TABLE spaces ADD content_id INT DEFAULT NULL, ADD reference VARCHAR(255) DEFAULT NULL, CHANGE availability_start availability_start DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B647884A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD2B647884A0A3ED ON spaces (content_id)');
        $this->addSql('ALTER TABLE users ADD content_id INT DEFAULT NULL, ADD image_id INT DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E984A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E93DA5256D FOREIGN KEY (image_id) REFERENCES space_images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E984A0A3ED ON users (content_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E93DA5256D ON users (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B647884A0A3ED');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E984A0A3ED');
        $this->addSql('CREATE TABLE space_reviews (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, review_date DATETIME NOT NULL, rating INT DEFAULT NULL, comment LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E9643047A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE space_translations (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, language VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_7FCD647023575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE space_reviews ADD CONSTRAINT FK_E9643047A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE space_translations ADD CONSTRAINT FK_7FCD647023575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FA76ED395');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F23575340');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F3E2E969B');
        $this->addSql('DROP TABLE contents');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('ALTER TABLE payments DROP reference, DROP stripe_token, DROP stripe_brand, DROP stripe_last4, DROP name, DROP email, CHANGE created_at payment_date DATETIME NOT NULL, CHANGE stripe_status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE reservations DROP reference, DROP stripe_token, DROP brand_stripe, DROP last4_stripe, DROP id_charge_stripe, DROP status_stripe, CHANGE created_at reservation_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE space_equipements ADD quantity INT DEFAULT NULL, ADD equipped TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE space_images CHANGE image_path image_path VARCHAR(50) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_DD2B647884A0A3ED ON spaces');
        $this->addSql('ALTER TABLE spaces DROP content_id, DROP reference, CHANGE availability_start availability_start DATETIME NOT NULL');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E93DA5256D');
        $this->addSql('DROP INDEX UNIQ_1483A5E984A0A3ED ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E93DA5256D ON users');
        $this->addSql('ALTER TABLE users DROP content_id, DROP image_id, DROP is_verified');
    }
}

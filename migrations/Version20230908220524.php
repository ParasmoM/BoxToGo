<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230908220524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Établissement de toutes les relations entre les tables de la base de données.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE space_amenity_links_space_amenities (space_amenity_links_id INT NOT NULL, space_amenities_id INT NOT NULL, INDEX IDX_51A5511F739EE0AF (space_amenity_links_id), INDEX IDX_51A5511F57EC95 (space_amenities_id), PRIMARY KEY(space_amenity_links_id, space_amenities_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE space_amenity_links_space_amenities ADD CONSTRAINT FK_51A5511F739EE0AF FOREIGN KEY (space_amenity_links_id) REFERENCES space_amenity_links (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE space_amenity_links_space_amenities ADD CONSTRAINT FK_51A5511F57EC95 FOREIGN KEY (space_amenities_id) REFERENCES space_amenities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversations ADD sent_by_user_id INT DEFAULT NULL, ADD received_by_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF1CF855BB3 FOREIGN KEY (sent_by_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF17C1D6AE1 FOREIGN KEY (received_by_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C2521BF1CF855BB3 ON conversations (sent_by_user_id)');
        $this->addSql('CREATE INDEX IDX_C2521BF17C1D6AE1 ON conversations (received_by_user_id)');
        $this->addSql('ALTER TABLE favorite_spaces ADD spaces_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorite_spaces ADD CONSTRAINT FK_E19E0882918EABE6 FOREIGN KEY (spaces_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE favorite_spaces ADD CONSTRAINT FK_E19E0882A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E19E0882918EABE6 ON favorite_spaces (spaces_id)');
        $this->addSql('CREATE INDEX IDX_E19E0882A76ED395 ON favorite_spaces (user_id)');
        $this->addSql('ALTER TABLE images ADD spaces_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A918EABE6 FOREIGN KEY (spaces_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A918EABE6 ON images (spaces_id)');
        $this->addSql('ALTER TABLE payments ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_65D29B32A76ED395 ON payments (user_id)');
        $this->addSql('ALTER TABLE reservations ADD user_id INT DEFAULT NULL, ADD space_id INT DEFAULT NULL, ADD payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23923575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2394C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
        $this->addSql('CREATE INDEX IDX_4DA239A76ED395 ON reservations (user_id)');
        $this->addSql('CREATE INDEX IDX_4DA23923575340 ON reservations (space_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DA2394C3A3BB ON reservations (payment_id)');
        $this->addSql('ALTER TABLE reviews ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6970EB0FA76ED395 ON reviews (user_id)');
        $this->addSql('ALTER TABLE space_amenity_links ADD spaces_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_amenity_links ADD CONSTRAINT FK_EC73EA4D918EABE6 FOREIGN KEY (spaces_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_EC73EA4D918EABE6 ON space_amenity_links (spaces_id)');
        $this->addSql('ALTER TABLE spaces ADD type_id INT DEFAULT NULL, ADD adresse_id INT DEFAULT NULL, ADD content_id INT DEFAULT NULL, ADD rented_by_user_id INT DEFAULT NULL, ADD owned_by_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B6478C54C8C93 FOREIGN KEY (type_id) REFERENCES space_types (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64784DE7DC5C FOREIGN KEY (adresse_id) REFERENCES addresses (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B647884A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64788A29C5CA FOREIGN KEY (rented_by_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64786DD3F56D FOREIGN KEY (owned_by_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DD2B6478C54C8C93 ON spaces (type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD2B64784DE7DC5C ON spaces (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD2B647884A0A3ED ON spaces (content_id)');
        $this->addSql('CREATE INDEX IDX_DD2B64788A29C5CA ON spaces (rented_by_user_id)');
        $this->addSql('CREATE INDEX IDX_DD2B64786DD3F56D ON spaces (owned_by_user_id)');
        $this->addSql('ALTER TABLE user ADD adresse_id INT DEFAULT NULL, ADD image_id INT DEFAULT NULL, ADD content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DE7DC5C FOREIGN KEY (adresse_id) REFERENCES addresses (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64984A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494DE7DC5C ON user (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493DA5256D ON user (image_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64984A0A3ED ON user (content_id)');
        $this->addSql('ALTER TABLE user_consent ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_consent ADD CONSTRAINT FK_3B1F161AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3B1F161AA76ED395 ON user_consent (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_amenity_links_space_amenities DROP FOREIGN KEY FK_51A5511F739EE0AF');
        $this->addSql('ALTER TABLE space_amenity_links_space_amenities DROP FOREIGN KEY FK_51A5511F57EC95');
        $this->addSql('DROP TABLE space_amenity_links_space_amenities');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF1CF855BB3');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF17C1D6AE1');
        $this->addSql('DROP INDEX IDX_C2521BF1CF855BB3 ON conversations');
        $this->addSql('DROP INDEX IDX_C2521BF17C1D6AE1 ON conversations');
        $this->addSql('ALTER TABLE conversations DROP sent_by_user_id, DROP received_by_user_id');
        $this->addSql('ALTER TABLE favorite_spaces DROP FOREIGN KEY FK_E19E0882918EABE6');
        $this->addSql('ALTER TABLE favorite_spaces DROP FOREIGN KEY FK_E19E0882A76ED395');
        $this->addSql('DROP INDEX IDX_E19E0882918EABE6 ON favorite_spaces');
        $this->addSql('DROP INDEX IDX_E19E0882A76ED395 ON favorite_spaces');
        $this->addSql('ALTER TABLE favorite_spaces DROP spaces_id, DROP user_id');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A918EABE6');
        $this->addSql('DROP INDEX IDX_E01FBE6A918EABE6 ON images');
        $this->addSql('ALTER TABLE images DROP spaces_id');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B32A76ED395');
        $this->addSql('DROP INDEX IDX_65D29B32A76ED395 ON payments');
        $this->addSql('ALTER TABLE payments DROP user_id');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23923575340');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2394C3A3BB');
        $this->addSql('DROP INDEX IDX_4DA239A76ED395 ON reservations');
        $this->addSql('DROP INDEX IDX_4DA23923575340 ON reservations');
        $this->addSql('DROP INDEX UNIQ_4DA2394C3A3BB ON reservations');
        $this->addSql('ALTER TABLE reservations DROP user_id, DROP space_id, DROP payment_id');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FA76ED395');
        $this->addSql('DROP INDEX IDX_6970EB0FA76ED395 ON reviews');
        $this->addSql('ALTER TABLE reviews DROP user_id');
        $this->addSql('ALTER TABLE space_amenity_links DROP FOREIGN KEY FK_EC73EA4D918EABE6');
        $this->addSql('DROP INDEX IDX_EC73EA4D918EABE6 ON space_amenity_links');
        $this->addSql('ALTER TABLE space_amenity_links DROP spaces_id');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B6478C54C8C93');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64784DE7DC5C');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B647884A0A3ED');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64788A29C5CA');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64786DD3F56D');
        $this->addSql('DROP INDEX IDX_DD2B6478C54C8C93 ON spaces');
        $this->addSql('DROP INDEX UNIQ_DD2B64784DE7DC5C ON spaces');
        $this->addSql('DROP INDEX UNIQ_DD2B647884A0A3ED ON spaces');
        $this->addSql('DROP INDEX IDX_DD2B64788A29C5CA ON spaces');
        $this->addSql('DROP INDEX IDX_DD2B64786DD3F56D ON spaces');
        $this->addSql('ALTER TABLE spaces DROP type_id, DROP adresse_id, DROP content_id, DROP rented_by_user_id, DROP owned_by_user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DE7DC5C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493DA5256D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64984A0A3ED');
        $this->addSql('DROP INDEX UNIQ_8D93D6494DE7DC5C ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6493DA5256D ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64984A0A3ED ON user');
        $this->addSql('ALTER TABLE user DROP adresse_id, DROP image_id, DROP content_id');
        $this->addSql('ALTER TABLE user_consent DROP FOREIGN KEY FK_3B1F161AA76ED395');
        $this->addSql('DROP INDEX IDX_3B1F161AA76ED395 ON user_consent');
        $this->addSql('ALTER TABLE user_consent DROP user_id');
    }
}

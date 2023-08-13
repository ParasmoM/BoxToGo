<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230812214835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ce script établit les relations globales entre les tables.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, INDEX IDX_C35F081623575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F081623575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE article_content ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_content ADD CONSTRAINT FK_1317741E7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('CREATE INDEX IDX_1317741E7294869C ON article_content (article_id)');
        $this->addSql('ALTER TABLE article_log ADD admin_id INT DEFAULT NULL, ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_log ADD CONSTRAINT FK_1D204BBF642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE article_log ADD CONSTRAINT FK_1D204BBF7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('CREATE INDEX IDX_1D204BBF642B8210 ON article_log (admin_id)');
        $this->addSql('CREATE INDEX IDX_1D204BBF7294869C ON article_log (article_id)');
        $this->addSql('ALTER TABLE articles ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_BFDD3168642B8210 ON articles (admin_id)');
        $this->addSql('ALTER TABLE conversations ADD user_id INT DEFAULT NULL, ADD host_id INT DEFAULT NULL, ADD space_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF11FB8D185 FOREIGN KEY (host_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF123575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_C2521BF1A76ED395 ON conversations (user_id)');
        $this->addSql('CREATE INDEX IDX_C2521BF11FB8D185 ON conversations (host_id)');
        $this->addSql('CREATE INDEX IDX_C2521BF123575340 ON conversations (space_id)');
        $this->addSql('ALTER TABLE favoris ADD user_id INT DEFAULT NULL, ADD space_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43223575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_8933C432A76ED395 ON favoris (user_id)');
        $this->addSql('CREATE INDEX IDX_8933C43223575340 ON favoris (space_id)');
        $this->addSql('ALTER TABLE messages ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DB021E96A76ED395 ON messages (user_id)');
        $this->addSql('ALTER TABLE notifications ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_6000B0D3A76ED395 ON notifications (user_id)');
        $this->addSql('ALTER TABLE payments ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_65D29B32A76ED395 ON payments (user_id)');
        $this->addSql('ALTER TABLE reports ADD user_id INT DEFAULT NULL, ADD host_id INT DEFAULT NULL, ADD space_id INT DEFAULT NULL, ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA745A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA7451FB8D185 FOREIGN KEY (host_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA74523575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA745642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_F11FA745A76ED395 ON reports (user_id)');
        $this->addSql('CREATE INDEX IDX_F11FA7451FB8D185 ON reports (host_id)');
        $this->addSql('CREATE INDEX IDX_F11FA74523575340 ON reports (space_id)');
        $this->addSql('CREATE INDEX IDX_F11FA745642B8210 ON reports (admin_id)');
        $this->addSql('ALTER TABLE reservations ADD user_id INT DEFAULT NULL, ADD space_id INT DEFAULT NULL, ADD payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23923575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2394C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
        $this->addSql('CREATE INDEX IDX_4DA239A76ED395 ON reservations (user_id)');
        $this->addSql('CREATE INDEX IDX_4DA23923575340 ON reservations (space_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DA2394C3A3BB ON reservations (payment_id)');
        $this->addSql('ALTER TABLE space_equipement_link ADD space_id INT DEFAULT NULL, ADD equipment_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_equipement_link ADD CONSTRAINT FK_A36D7E3923575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE space_equipement_link ADD CONSTRAINT FK_A36D7E3976C5339 FOREIGN KEY (equipment_name_id) REFERENCES space_equipements (id)');
        $this->addSql('CREATE INDEX IDX_A36D7E3923575340 ON space_equipement_link (space_id)');
        $this->addSql('CREATE INDEX IDX_A36D7E3976C5339 ON space_equipement_link (equipment_name_id)');
        $this->addSql('ALTER TABLE space_images ADD space_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_images ADD CONSTRAINT FK_91A33DFF23575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_91A33DFF23575340 ON space_images (space_id)');
        $this->addSql('ALTER TABLE space_reviews ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_reviews ADD CONSTRAINT FK_E9643047A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_E9643047A76ED395 ON space_reviews (user_id)');
        $this->addSql('ALTER TABLE space_translations ADD space_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_translations ADD CONSTRAINT FK_7FCD647023575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_7FCD647023575340 ON space_translations (space_id)');
        $this->addSql('ALTER TABLE spaces ADD user_id INT DEFAULT NULL, ADD host_id INT DEFAULT NULL, ADD space_categ_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B6478A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64781FB8D185 FOREIGN KEY (host_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64789090866B FOREIGN KEY (space_categ_id) REFERENCES space_categories (id)');
        $this->addSql('CREATE INDEX IDX_DD2B6478A76ED395 ON spaces (user_id)');
        $this->addSql('CREATE INDEX IDX_DD2B64781FB8D185 ON spaces (host_id)');
        $this->addSql('CREATE INDEX IDX_DD2B64789090866B ON spaces (space_categ_id)');
        $this->addSql('ALTER TABLE user_consent ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_consent ADD CONSTRAINT FK_3B1F161AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_3B1F161AA76ED395 ON user_consent (user_id)');
        $this->addSql('ALTER TABLE users ADD adresse_id INT DEFAULT NULL, ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E94DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresses (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E94DE7DC5C ON users (adresse_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9642B8210 ON users (admin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F081623575340');
        // $this->addSql('DROP TABLE adresse');
        $this->addSql('ALTER TABLE article_content DROP FOREIGN KEY FK_1317741E7294869C');
        $this->addSql('DROP INDEX IDX_1317741E7294869C ON article_content');
        $this->addSql('ALTER TABLE article_content DROP article_id');
        $this->addSql('ALTER TABLE article_log DROP FOREIGN KEY FK_1D204BBF642B8210');
        $this->addSql('ALTER TABLE article_log DROP FOREIGN KEY FK_1D204BBF7294869C');
        $this->addSql('DROP INDEX IDX_1D204BBF642B8210 ON article_log');
        $this->addSql('DROP INDEX IDX_1D204BBF7294869C ON article_log');
        $this->addSql('ALTER TABLE article_log DROP admin_id, DROP article_id');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168642B8210');
        $this->addSql('DROP INDEX IDX_BFDD3168642B8210 ON articles');
        $this->addSql('ALTER TABLE articles DROP admin_id');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF1A76ED395');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF11FB8D185');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF123575340');
        $this->addSql('DROP INDEX IDX_C2521BF1A76ED395 ON conversations');
        $this->addSql('DROP INDEX IDX_C2521BF11FB8D185 ON conversations');
        $this->addSql('DROP INDEX IDX_C2521BF123575340 ON conversations');
        $this->addSql('ALTER TABLE conversations DROP user_id, DROP host_id, DROP space_id');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43223575340');
        $this->addSql('DROP INDEX IDX_8933C432A76ED395 ON favoris');
        $this->addSql('DROP INDEX IDX_8933C43223575340 ON favoris');
        $this->addSql('ALTER TABLE favoris DROP user_id, DROP space_id');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('DROP INDEX IDX_DB021E96A76ED395 ON messages');
        $this->addSql('ALTER TABLE messages DROP user_id');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('DROP INDEX IDX_6000B0D3A76ED395 ON notifications');
        $this->addSql('ALTER TABLE notifications DROP user_id');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B32A76ED395');
        $this->addSql('DROP INDEX IDX_65D29B32A76ED395 ON payments');
        $this->addSql('ALTER TABLE payments DROP user_id');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA745A76ED395');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA7451FB8D185');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA74523575340');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA745642B8210');
        $this->addSql('DROP INDEX IDX_F11FA745A76ED395 ON reports');
        $this->addSql('DROP INDEX IDX_F11FA7451FB8D185 ON reports');
        $this->addSql('DROP INDEX IDX_F11FA74523575340 ON reports');
        $this->addSql('DROP INDEX IDX_F11FA745642B8210 ON reports');
        $this->addSql('ALTER TABLE reports DROP user_id, DROP host_id, DROP space_id, DROP admin_id');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23923575340');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2394C3A3BB');
        $this->addSql('DROP INDEX IDX_4DA239A76ED395 ON reservations');
        $this->addSql('DROP INDEX IDX_4DA23923575340 ON reservations');
        $this->addSql('DROP INDEX UNIQ_4DA2394C3A3BB ON reservations');
        $this->addSql('ALTER TABLE reservations DROP user_id, DROP space_id, DROP payment_id');
        $this->addSql('ALTER TABLE space_equipement_link DROP FOREIGN KEY FK_A36D7E3923575340');
        $this->addSql('ALTER TABLE space_equipement_link DROP FOREIGN KEY FK_A36D7E3976C5339');
        $this->addSql('DROP INDEX IDX_A36D7E3923575340 ON space_equipement_link');
        $this->addSql('DROP INDEX IDX_A36D7E3976C5339 ON space_equipement_link');
        $this->addSql('ALTER TABLE space_equipement_link DROP space_id, DROP equipment_name_id');
        $this->addSql('ALTER TABLE space_images DROP FOREIGN KEY FK_91A33DFF23575340');
        $this->addSql('DROP INDEX IDX_91A33DFF23575340 ON space_images');
        $this->addSql('ALTER TABLE space_images DROP space_id');
        $this->addSql('ALTER TABLE space_reviews DROP FOREIGN KEY FK_E9643047A76ED395');
        $this->addSql('DROP INDEX IDX_E9643047A76ED395 ON space_reviews');
        $this->addSql('ALTER TABLE space_reviews DROP user_id');
        $this->addSql('ALTER TABLE space_translations DROP FOREIGN KEY FK_7FCD647023575340');
        $this->addSql('DROP INDEX IDX_7FCD647023575340 ON space_translations');
        $this->addSql('ALTER TABLE space_translations DROP space_id');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B6478A76ED395');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64781FB8D185');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64789090866B');
        $this->addSql('DROP INDEX IDX_DD2B6478A76ED395 ON spaces');
        $this->addSql('DROP INDEX IDX_DD2B64781FB8D185 ON spaces');
        $this->addSql('DROP INDEX IDX_DD2B64789090866B ON spaces');
        $this->addSql('ALTER TABLE spaces DROP user_id, DROP host_id, DROP space_categ_id');
        $this->addSql('ALTER TABLE user_consent DROP FOREIGN KEY FK_3B1F161AA76ED395');
        $this->addSql('DROP INDEX IDX_3B1F161AA76ED395 ON user_consent');
        $this->addSql('ALTER TABLE user_consent DROP user_id');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E94DE7DC5C');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9642B8210');
        $this->addSql('DROP INDEX UNIQ_1483A5E94DE7DC5C ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E9642B8210 ON users');
        $this->addSql('ALTER TABLE users DROP adresse_id, DROP admin_id');
    }
}

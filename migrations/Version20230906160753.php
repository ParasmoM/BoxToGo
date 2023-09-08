<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230906160753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changement du nom de la table "conversation" en "talks" et modification des noms de champs.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE talks (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, space_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_472281DAF624B39D (sender_id), INDEX IDX_472281DACD53EDB6 (receiver_id), INDEX IDX_472281DA23575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE talks ADD CONSTRAINT FK_472281DAF624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE talks ADD CONSTRAINT FK_472281DACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE talks ADD CONSTRAINT FK_472281DA23575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF123575340');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF1A76ED395');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF11FB8D185');
        $this->addSql('DROP TABLE conversations');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversations (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, host_id INT DEFAULT NULL, space_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_C2521BF1A76ED395 (user_id), INDEX IDX_C2521BF123575340 (space_id), INDEX IDX_C2521BF11FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF123575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF11FB8D185 FOREIGN KEY (host_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE talks DROP FOREIGN KEY FK_472281DAF624B39D');
        $this->addSql('ALTER TABLE talks DROP FOREIGN KEY FK_472281DACD53EDB6');
        $this->addSql('ALTER TABLE talks DROP FOREIGN KEY FK_472281DA23575340');
        $this->addSql('DROP TABLE talks');
    }
}

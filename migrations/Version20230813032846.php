<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813032846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Établissement de la liaison entre Spaces et Adresses.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresses ADD space_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT FK_EF19255223575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_EF19255223575340 ON adresses (space_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY FK_EF19255223575340');
        $this->addSql('DROP INDEX IDX_EF19255223575340 ON adresses');
        $this->addSql('ALTER TABLE adresses DROP space_id');
    }
}

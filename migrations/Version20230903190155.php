<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903190155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Pour supprimer de la table le champ quantity et equipped';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_equipements DROP quantity, DROP equipped');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_equipements ADD quantity INT DEFAULT NULL, ADD equipped TINYINT(1) NOT NULL');
    }
}

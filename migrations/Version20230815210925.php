<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815210925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rend le champ "availabilityEnd" de la table "Spaces" optionnel.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spaces CHANGE availability_end availability_end DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spaces CHANGE availability_end availability_end DATETIME NOT NULL');
    }
}

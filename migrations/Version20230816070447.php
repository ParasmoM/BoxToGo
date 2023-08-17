<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230816070447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Pour déplacer les champs 'quantity' et 'equipped' de la table 'SpaceEquipementLink' à la table 'SpaceEquipement'.";
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_equipement_link DROP quantity, DROP equipped');
        $this->addSql('ALTER TABLE space_equipements ADD quantity INT DEFAULT NULL, ADD equipped TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_equipement_link ADD quantity INT DEFAULT NULL, ADD equipped TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE space_equipements DROP quantity, DROP equipped');
    }
}

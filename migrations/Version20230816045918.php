<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230816045918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Passage de la relation entre spaceEquipments et spaceEquipmentLink en ManyToMany.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE space_equipement_link_space_equipements (space_equipement_link_id INT NOT NULL, space_equipements_id INT NOT NULL, INDEX IDX_A68F315FCF445211 (space_equipement_link_id), INDEX IDX_A68F315FF2089F7E (space_equipements_id), PRIMARY KEY(space_equipement_link_id, space_equipements_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements ADD CONSTRAINT FK_A68F315FCF445211 FOREIGN KEY (space_equipement_link_id) REFERENCES space_equipement_link (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements ADD CONSTRAINT FK_A68F315FF2089F7E FOREIGN KEY (space_equipements_id) REFERENCES space_equipements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE space_equipement_link DROP FOREIGN KEY FK_A36D7E3976C5339');
        $this->addSql('DROP INDEX IDX_A36D7E3976C5339 ON space_equipement_link');
        $this->addSql('ALTER TABLE space_equipement_link DROP equipment_name_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements DROP FOREIGN KEY FK_A68F315FCF445211');
        $this->addSql('ALTER TABLE space_equipement_link_space_equipements DROP FOREIGN KEY FK_A68F315FF2089F7E');
        $this->addSql('DROP TABLE space_equipement_link_space_equipements');
        $this->addSql('ALTER TABLE space_equipement_link ADD equipment_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_equipement_link ADD CONSTRAINT FK_A36D7E3976C5339 FOREIGN KEY (equipment_name_id) REFERENCES space_equipements (id)');
        $this->addSql('CREATE INDEX IDX_A36D7E3976C5339 ON space_equipement_link (equipment_name_id)');
    }
}

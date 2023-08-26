<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825172054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Pour ajuster le nombre de caractères autorisés dans le champ imagePath de SpaceImages.";
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_images CHANGE image_path image_path VARCHAR(250) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_images CHANGE image_path image_path VARCHAR(50) NOT NULL');
    }
}

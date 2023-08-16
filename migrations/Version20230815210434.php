<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815210434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cette migration a été utilisée pour changer le nom du champ de "condition" à "itemCondition".';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spaces CHANGE `condition` item_condition VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spaces CHANGE item_condition `condition` VARCHAR(50) DEFAULT NULL');
    }
}

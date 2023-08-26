<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825154445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout d\'une nouvelle rélations entre USERS et SPACEIMAGES en OneToOne';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E93DA5256D FOREIGN KEY (image_id) REFERENCES space_images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E93DA5256D ON users (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E93DA5256D');
        $this->addSql('DROP INDEX UNIQ_1483A5E93DA5256D ON users');
        $this->addSql('ALTER TABLE users DROP image_id');
    }
}

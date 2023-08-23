<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822172307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Nouvelle rélation de Spaces à SpaceReviews';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_reviews ADD space_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_reviews ADD CONSTRAINT FK_E964304723575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_E964304723575340 ON space_reviews (space_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_reviews DROP FOREIGN KEY FK_E964304723575340');
        $this->addSql('DROP INDEX IDX_E964304723575340 ON space_reviews');
        $this->addSql('ALTER TABLE space_reviews DROP space_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822182532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews ADD review_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F3E2E969B FOREIGN KEY (review_id) REFERENCES reviews (id)');
        $this->addSql('CREATE INDEX IDX_6970EB0F3E2E969B ON reviews (review_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F3E2E969B');
        $this->addSql('DROP INDEX IDX_6970EB0F3E2E969B ON reviews');
        $this->addSql('ALTER TABLE reviews DROP review_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831170646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de divers champs à la table Payments.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments ADD reference VARCHAR(255) DEFAULT NULL, ADD stripe_token VARCHAR(255) DEFAULT NULL, ADD stripe_brand VARCHAR(255) DEFAULT NULL, ADD stripe_last4 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments DROP reference, DROP stripe_token, DROP stripe_brand, DROP stripe_last4');
    }
}

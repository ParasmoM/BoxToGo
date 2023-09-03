<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831151002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de divers champs à la table Reservations.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD reference VARCHAR(255) DEFAULT NULL, ADD stripe_token VARCHAR(255) DEFAULT NULL, ADD brand_stripe VARCHAR(255) DEFAULT NULL, ADD last4_stripe VARCHAR(255) DEFAULT NULL, ADD id_charge_stripe VARCHAR(255) DEFAULT NULL, ADD status_stripe VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP reference, DROP stripe_token, DROP brand_stripe, DROP last4_stripe, DROP id_charge_stripe, DROP status_stripe');
    }
}

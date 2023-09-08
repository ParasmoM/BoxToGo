<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905231852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_translations DROP FOREIGN KEY FK_7FCD647023575340');
        $this->addSql('DROP TABLE space_translations');
        $this->addSql('ALTER TABLE spaces ADD content_id INT DEFAULT NULL, CHANGE availability_start availability_start DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B647884A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD2B647884A0A3ED ON spaces (content_id)');
        $this->addSql('ALTER TABLE users ADD content_id INT DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E984A0A3ED FOREIGN KEY (content_id) REFERENCES contents (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E984A0A3ED ON users (content_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE space_translations (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, language VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_7FCD647023575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE space_translations ADD CONSTRAINT FK_7FCD647023575340 FOREIGN KEY (space_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B647884A0A3ED');
        $this->addSql('DROP INDEX UNIQ_DD2B647884A0A3ED ON spaces');
        $this->addSql('ALTER TABLE spaces DROP content_id, CHANGE availability_start availability_start DATETIME NOT NULL');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E984A0A3ED');
        $this->addSql('DROP INDEX UNIQ_1483A5E984A0A3ED ON users');
        $this->addSql('ALTER TABLE users DROP content_id, DROP is_verified');
    }
}

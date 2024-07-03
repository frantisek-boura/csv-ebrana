<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702134600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colour (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_FAF865CE5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, colour_id_id INT NOT NULL, code VARCHAR(20) NOT NULL, ean13 VARCHAR(13) NOT NULL, dun14 VARCHAR(14) NOT NULL, carton_qty INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, size INT DEFAULT NULL, description VARCHAR(255) NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, image_path VARCHAR(512) DEFAULT NULL, UNIQUE INDEX UNIQ_1F1B251E77153098 (code), UNIQUE INDEX UNIQ_1F1B251E2FAE1FC8 (ean13), UNIQUE INDEX UNIQ_1F1B251E53D16313 (dun14), INDEX IDX_1F1B251E74CD3D7C (colour_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E74CD3D7C FOREIGN KEY (colour_id_id) REFERENCES colour (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E74CD3D7C');
        $this->addSql('DROP TABLE colour');
        $this->addSql('DROP TABLE item');
    }
}

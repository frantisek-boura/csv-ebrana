<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703000500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E74CD3D7C');
        $this->addSql('DROP INDEX IDX_1F1B251E74CD3D7C ON item');
        $this->addSql('ALTER TABLE item CHANGE colour_id_id colour_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E569C9B4C FOREIGN KEY (colour_id) REFERENCES colour (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E569C9B4C ON item (colour_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E569C9B4C');
        $this->addSql('DROP INDEX IDX_1F1B251E569C9B4C ON item');
        $this->addSql('ALTER TABLE item CHANGE colour_id colour_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E74CD3D7C FOREIGN KEY (colour_id_id) REFERENCES colour (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1F1B251E74CD3D7C ON item (colour_id_id)');
    }
}

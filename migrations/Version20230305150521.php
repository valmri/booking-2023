<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305150521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD spectacle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C682915D FOREIGN KEY (spectacle_id) REFERENCES `show` (id)');
        $this->addSql('CREATE INDEX IDX_42C84955C682915D ON reservation (spectacle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C682915D');
        $this->addSql('DROP INDEX IDX_42C84955C682915D ON reservation');
        $this->addSql('ALTER TABLE reservation DROP spectacle_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305145338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seat DROP FOREIGN KEY FK_3D5C3666B83297E7');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559D86650F');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP INDEX IDX_3D5C3666B83297E7 ON seat');
        $this->addSql('ALTER TABLE seat DROP reservation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_reservation DATE NOT NULL, INDEX IDX_42C849559D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE seat ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C3666B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_3D5C3666B83297E7 ON seat (reservation_id)');
    }
}

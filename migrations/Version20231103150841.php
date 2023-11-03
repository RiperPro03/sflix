<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103150841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, img VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie_utilisateur (serie_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_1A2051E0D94388BD (serie_id), INDEX IDX_1A2051E0FB88E14F (utilisateur_id), PRIMARY KEY(serie_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE serie_utilisateur ADD CONSTRAINT FK_1A2051E0D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE serie_utilisateur ADD CONSTRAINT FK_1A2051E0FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serie_utilisateur DROP FOREIGN KEY FK_1A2051E0D94388BD');
        $this->addSql('ALTER TABLE serie_utilisateur DROP FOREIGN KEY FK_1A2051E0FB88E14F');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE serie_utilisateur');
        $this->addSql('DROP TABLE utilisateur');
    }
}

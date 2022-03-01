<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301132239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, taille NUMERIC(15, 2) NOT NULL, original VARCHAR(255) NOT NULL, INDEX IDX_C53D045FF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('DROP TABLE utilisateurs_produits');
        $this->addSql('ALTER TABLE commandes ADD utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C1E969C5 ON commandes (utilisateurs_id)');
        $this->addSql('ALTER TABLE produits ADD prix NUMERIC(15, 2) NOT NULL, ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateurs_produits (utilisateurs_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_A5228C9ECD11A2CF (produits_id), INDEX IDX_A5228C9E1E969C5 (utilisateurs_id), PRIMARY KEY(utilisateurs_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9ECD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9E1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C1E969C5');
        $this->addSql('DROP INDEX IDX_35D4282C1E969C5 ON commandes');
        $this->addSql('ALTER TABLE commandes DROP utilisateurs_id');
        $this->addSql('ALTER TABLE produits DROP prix, DROP description');
    }
}

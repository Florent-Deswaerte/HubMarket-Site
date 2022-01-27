<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220112192527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, panier_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_497B315EE7927C74 (email), UNIQUE INDEX UNIQ_497B315EF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs_produits (utilisateurs_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_A5228C9E1E969C5 (utilisateurs_id), INDEX IDX_A5228C9ECD11A2CF (produits_id), PRIMARY KEY(utilisateurs_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315EF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9E1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9ECD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes ADD utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C1E969C5 ON commandes (utilisateurs_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C1E969C5');
        $this->addSql('ALTER TABLE utilisateurs_produits DROP FOREIGN KEY FK_A5228C9E1E969C5');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE utilisateurs_produits');
        $this->addSql('DROP INDEX IDX_35D4282C1E969C5 ON commandes');
        $this->addSql('ALTER TABLE commandes DROP utilisateurs_id');
    }
}

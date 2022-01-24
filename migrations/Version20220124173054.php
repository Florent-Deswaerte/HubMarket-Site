<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124173054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C1E969C5');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF21E969C5');
        $this->addSql('ALTER TABLE produits_utilisateurs DROP FOREIGN KEY FK_DAEF1CDC1E969C5');
        $this->addSql('DROP TABLE produits_utilisateurs');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP INDEX IDX_35D4282C1E969C5 ON commandes');
        $this->addSql('ALTER TABLE commandes DROP utilisateurs_id');
        $this->addSql('DROP INDEX UNIQ_24CC0DF21E969C5 ON panier');
        $this->addSql('ALTER TABLE panier DROP utilisateurs_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produits_utilisateurs (produits_id INT NOT NULL, utilisateurs_id INT NOT NULL, INDEX IDX_DAEF1CDC1E969C5 (utilisateurs_id), INDEX IDX_DAEF1CDCCD11A2CF (produits_id), PRIMARY KEY(produits_id, utilisateurs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code_postal INT NOT NULL, pays VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, mdp VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produits_utilisateurs ADD CONSTRAINT FK_DAEF1CDC1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_utilisateurs ADD CONSTRAINT FK_DAEF1CDCCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes ADD utilisateurs_id INT NOT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C1E969C5 ON commandes (utilisateurs_id)');
        $this->addSql('ALTER TABLE panier ADD utilisateurs_id INT NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF21E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24CC0DF21E969C5 ON panier (utilisateurs_id)');
    }
}

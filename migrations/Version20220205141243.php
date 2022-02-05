<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205141243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories_produits (categories_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_68D376B5A21214B7 (categories_id), INDEX IDX_68D376B5CD11A2CF (produits_id), PRIMARY KEY(categories_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseurs_produits (fournisseurs_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_384635D727ACDDFD (fournisseurs_id), INDEX IDX_384635D7CD11A2CF (produits_id), PRIMARY KEY(fournisseurs_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_produits (panier_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_2468D6FEF77D927C (panier_id), INDEX IDX_2468D6FECD11A2CF (produits_id), PRIMARY KEY(panier_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, qty INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs_produits (utilisateurs_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_A5228C9E1E969C5 (utilisateurs_id), INDEX IDX_A5228C9ECD11A2CF (produits_id), PRIMARY KEY(utilisateurs_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fournisseurs_produits ADD CONSTRAINT FK_384635D727ACDDFD FOREIGN KEY (fournisseurs_id) REFERENCES fournisseurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fournisseurs_produits ADD CONSTRAINT FK_384635D7CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produits ADD CONSTRAINT FK_2468D6FEF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produits ADD CONSTRAINT FK_2468D6FECD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9E1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9ECD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE roles');
        $this->addSql('ALTER TABLE lcommandes ADD commandes_id INT NOT NULL, ADD produits_id INT NOT NULL');
        $this->addSql('ALTER TABLE lcommandes ADD CONSTRAINT FK_FB8CB00A8BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE lcommandes ADD CONSTRAINT FK_FB8CB00ACD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_FB8CB00A8BF5C2E6 ON lcommandes (commandes_id)');
        $this->addSql('CREATE INDEX IDX_FB8CB00ACD11A2CF ON lcommandes (produits_id)');
        $this->addSql('ALTER TABLE utilisateurs ADD panier_id INT DEFAULT NULL, ADD email VARCHAR(180) NOT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD password VARCHAR(255) NOT NULL, DROP nom, DROP prenom, DROP adresse, DROP code_postal, DROP pays, DROP mdp');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315EF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315EE7927C74 ON utilisateurs (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315EF77D927C ON utilisateurs (panier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_produits DROP FOREIGN KEY FK_2468D6FEF77D927C');
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315EF77D927C');
        $this->addSql('ALTER TABLE categories_produits DROP FOREIGN KEY FK_68D376B5CD11A2CF');
        $this->addSql('ALTER TABLE fournisseurs_produits DROP FOREIGN KEY FK_384635D7CD11A2CF');
        $this->addSql('ALTER TABLE lcommandes DROP FOREIGN KEY FK_FB8CB00ACD11A2CF');
        $this->addSql('ALTER TABLE panier_produits DROP FOREIGN KEY FK_2468D6FECD11A2CF');
        $this->addSql('ALTER TABLE utilisateurs_produits DROP FOREIGN KEY FK_A5228C9ECD11A2CF');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE categories_produits');
        $this->addSql('DROP TABLE fournisseurs_produits');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_produits');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE utilisateurs_produits');
        $this->addSql('ALTER TABLE lcommandes DROP FOREIGN KEY FK_FB8CB00A8BF5C2E6');
        $this->addSql('DROP INDEX IDX_FB8CB00A8BF5C2E6 ON lcommandes');
        $this->addSql('DROP INDEX IDX_FB8CB00ACD11A2CF ON lcommandes');
        $this->addSql('ALTER TABLE lcommandes DROP commandes_id, DROP produits_id');
        $this->addSql('DROP INDEX UNIQ_497B315EE7927C74 ON utilisateurs');
        $this->addSql('DROP INDEX UNIQ_497B315EF77D927C ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs ADD prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD code_postal INT NOT NULL, ADD pays VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD mdp VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP panier_id, DROP email, DROP roles, CHANGE password nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

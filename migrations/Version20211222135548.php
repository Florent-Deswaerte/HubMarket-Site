<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222135548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fournisseurs_produits (fournisseurs_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_384635D727ACDDFD (fournisseurs_id), INDEX IDX_384635D7CD11A2CF (produits_id), PRIMARY KEY(fournisseurs_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fournisseurs_produits ADD CONSTRAINT FK_384635D727ACDDFD FOREIGN KEY (fournisseurs_id) REFERENCES fournisseurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fournisseurs_produits ADD CONSTRAINT FK_384635D7CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lcommandes ADD commandes_id INT NOT NULL, ADD produits_id INT NOT NULL');
        $this->addSql('ALTER TABLE lcommandes ADD CONSTRAINT FK_FB8CB00A8BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE lcommandes ADD CONSTRAINT FK_FB8CB00ACD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_FB8CB00A8BF5C2E6 ON lcommandes (commandes_id)');
        $this->addSql('CREATE INDEX IDX_FB8CB00ACD11A2CF ON lcommandes (produits_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fournisseurs_produits');
        $this->addSql('ALTER TABLE lcommandes DROP FOREIGN KEY FK_FB8CB00A8BF5C2E6');
        $this->addSql('ALTER TABLE lcommandes DROP FOREIGN KEY FK_FB8CB00ACD11A2CF');
        $this->addSql('DROP INDEX IDX_FB8CB00A8BF5C2E6 ON lcommandes');
        $this->addSql('DROP INDEX IDX_FB8CB00ACD11A2CF ON lcommandes');
        $this->addSql('ALTER TABLE lcommandes DROP commandes_id, DROP produits_id');
    }
}

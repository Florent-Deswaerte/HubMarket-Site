<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222141229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, utilisateurs_id INT NOT NULL, UNIQUE INDEX UNIQ_24CC0DF21E969C5 (utilisateurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_utilisateurs (produits_id INT NOT NULL, utilisateurs_id INT NOT NULL, INDEX IDX_DAEF1CDCCD11A2CF (produits_id), INDEX IDX_DAEF1CDC1E969C5 (utilisateurs_id), PRIMARY KEY(produits_id, utilisateurs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF21E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE produits_utilisateurs ADD CONSTRAINT FK_DAEF1CDCCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_utilisateurs ADD CONSTRAINT FK_DAEF1CDC1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE produits_utilisateurs');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222140924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE utilisateurs_produits');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateurs_produits (utilisateurs_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_A5228C9E1E969C5 (utilisateurs_id), INDEX IDX_A5228C9ECD11A2CF (produits_id), PRIMARY KEY(utilisateurs_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9E1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_produits ADD CONSTRAINT FK_A5228C9ECD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
    }
}

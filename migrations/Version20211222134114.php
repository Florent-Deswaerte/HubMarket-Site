<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222134114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateurs DROP FOREIGN KEY FK_497B315E38C751C4');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP INDEX IDX_497B315E38C751C4 ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs DROP roles_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE utilisateurs ADD roles_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315E38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id)');
        $this->addSql('CREATE INDEX IDX_497B315E38C751C4 ON utilisateurs (roles_id)');
    }
}

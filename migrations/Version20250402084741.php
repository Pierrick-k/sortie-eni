<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402084741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->connection->executeStatement(<<<'SQL'
            INSERT INTO user (email, roles, password, nom, prenom, telephone, actif, administrateur) 
            VALUES ('admin@eni.fr', '{\"roles\":\"ROLE_ADMIN\"}', 'test', 'admin', 'test', '0123456789', 1, 0)
        SQL);/*
        $lastId = $this->connection->lastInsertId();
        dd($lastId);*/
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie ADD organisateur_id INT NOT NULL DEFAULT 1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2D936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C3FD3F2D936B2FA ON sortie (organisateur_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2D936B2FA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3C3FD3F2D936B2FA ON sortie
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie DROP organisateur_id
        SQL);
        $this->addSql(<<<'SQL'
            DELETE FROM user WHERE email = 'admin@eni.fr'
        SQL);
    }
}

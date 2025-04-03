<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403103323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
//         this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD campus_id INT DEFAULT NULL
        SQL);
        $this->addSql('UPDATE user SET campus_id = (SELECT id FROM campus ORDER BY id LIMIT 1) WHERE campus_id IS NULL');
        $this->addSql(<<<'SQL'
        ALTER TABLE user MODIFY campus_id INT NOT NULL
    SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649AF5D55E1 ON user (campus_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF5D55E1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649AF5D55E1 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP campus_id
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601203534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE enseignant_cours (enseignant_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_D6684A95E455FCC0 (enseignant_id), INDEX IDX_D6684A957ECF78B0 (cours_id), PRIMARY KEY(enseignant_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignant_cours ADD CONSTRAINT FK_D6684A95E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignant_cours ADD CONSTRAINT FK_D6684A957ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignant_cours DROP FOREIGN KEY FK_D6684A95E455FCC0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignant_cours DROP FOREIGN KEY FK_D6684A957ECF78B0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE enseignant_cours
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
        SQL);
    }
}

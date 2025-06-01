<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601231926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cours_enseignant (cours_id INT NOT NULL, enseignant_id INT NOT NULL, INDEX IDX_845FDD887ECF78B0 (cours_id), INDEX IDX_845FDD88E455FCC0 (enseignant_id), PRIMARY KEY(cours_id, enseignant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_enseignant ADD CONSTRAINT FK_845FDD887ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_enseignant ADD CONSTRAINT FK_845FDD88E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CE455FCC0
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_FDCA8C9CE455FCC0 ON cours
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours DROP enseignant_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_enseignant DROP FOREIGN KEY FK_845FDD887ECF78B0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_enseignant DROP FOREIGN KEY FK_845FDD88E455FCC0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cours_enseignant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours ADD enseignant_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FDCA8C9CE455FCC0 ON cours (enseignant_id)
        SQL);
    }
}

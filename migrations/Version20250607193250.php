<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607193250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cours_etudiant (etudiant_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_B6EA8C3EDDEAB1A3 (etudiant_id), INDEX IDX_B6EA8C3E7ECF78B0 (cours_id), PRIMARY KEY(etudiant_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_etudiant ADD CONSTRAINT FK_B6EA8C3EDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_etudiant ADD CONSTRAINT FK_B6EA8C3E7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_etudiant DROP FOREIGN KEY FK_B6EA8C3EDDEAB1A3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_etudiant DROP FOREIGN KEY FK_B6EA8C3E7ECF78B0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cours_etudiant
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240724124041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD focal_person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE
          project
        ADD
          CONSTRAINT FK_2FB3D0EEB833490D FOREIGN KEY (focal_person_id) REFERENCES personnel (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEB833490D ON project (focal_person_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEB833490D');
        $this->addSql('DROP INDEX IDX_2FB3D0EEB833490D ON project');
        $this->addSql('ALTER TABLE project DROP focal_person_id');
    }
}

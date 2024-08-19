<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240813165801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_session DROP FOREIGN KEY FK_196FC19C9B6B5FBA');
        $this->addSql('ALTER TABLE
          account_session
        ADD
          CONSTRAINT FK_196FC19C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DE166D1F9C');
        $this->addSql('ALTER TABLE
          personnel
        ADD
          CONSTRAINT FK_A6BCF3DE166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE
        SET
          NULL');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEB833490D');
        $this->addSql('ALTER TABLE
          project
        ADD
          CONSTRAINT FK_2FB3D0EEB833490D FOREIGN KEY (focal_person_id) REFERENCES personnel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DE166D1F9C');
        $this->addSql('ALTER TABLE
          personnel
        ADD
          CONSTRAINT FK_A6BCF3DE166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE account_session DROP FOREIGN KEY FK_196FC19C9B6B5FBA');
        $this->addSql('ALTER TABLE
          account_session
        ADD
          CONSTRAINT FK_196FC19C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEB833490D');
        $this->addSql('ALTER TABLE
          project
        ADD
          CONSTRAINT FK_2FB3D0EEB833490D FOREIGN KEY (focal_person_id) REFERENCES personnel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}

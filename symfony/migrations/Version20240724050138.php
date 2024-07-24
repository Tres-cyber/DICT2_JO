<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240724050138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_session (
          id INT AUTO_INCREMENT NOT NULL,
          account_id INT NOT NULL,
          login_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          logout_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_196FC19C9B6B5FBA (account_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          account_session
        ADD
          CONSTRAINT FK_196FC19C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE SESSION DROP FOREIGN KEY FK_D044D5D49B6B5FBA');
        $this->addSql('DROP TABLE SESSION');
        $this->addSql('ALTER TABLE
          account
        ADD
          CONSTRAINT FK_7D3656A4BE8425AD FOREIGN KEY (current_session_id) REFERENCES account_session (id)');
        $this->addSql('ALTER TABLE job_order CHANGE STATUS joborder_status VARCHAR(255) DEFAULT \'DRAFT\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4BE8425AD');
        $this->addSql('CREATE TABLE SESSION (
          id INT AUTO_INCREMENT NOT NULL,
          account_id INT NOT NULL,
          login DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
          logout DATETIME DEFAULT NULL,
          INDEX IDX_D044D5D49B6B5FBA (account_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql('ALTER TABLE
          SESSION
        ADD
          CONSTRAINT FK_D044D5D49B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE account_session DROP FOREIGN KEY FK_196FC19C9B6B5FBA');
        $this->addSql('DROP TABLE account_session');
        $this->addSql('ALTER TABLE job_order CHANGE joborder_status STATUS VARCHAR(255) DEFAULT \'DRAFT\' NOT NULL');
    }
}

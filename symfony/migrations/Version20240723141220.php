<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723141220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (
          id INT AUTO_INCREMENT NOT NULL,
          personnel_id INT DEFAULT NULL,
          current_session_id INT DEFAULT NULL,
          password_hash VARCHAR(127) NOT NULL,
          email VARCHAR(127) NOT NULL,
          is_deleted TINYINT(1) DEFAULT 0 NOT NULL,
          is_admin TINYINT(1) DEFAULT 0 NOT NULL,
          UNIQUE INDEX UNIQ_7D3656A4E7927C74 (email),
          UNIQUE INDEX UNIQ_7D3656A41C109075 (personnel_id),
          UNIQUE INDEX UNIQ_7D3656A4BE8425AD (current_session_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_order (
          id INT AUTO_INCREMENT NOT NULL,
          project_id INT NOT NULL,
          performer_id INT DEFAULT NULL,
          issuer_id INT DEFAULT NULL,
          approver_id INT DEFAULT NULL,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          scheduled_start_date DATE DEFAULT NULL,
          scheduled_end_date DATE DEFAULT NULL,
          job_description LONGTEXT DEFAULT NULL,
          start_time DATETIME DEFAULT NULL,
          end_time DATETIME DEFAULT NULL,
          actual_job_done LONGTEXT DEFAULT NULL,
          remarks LONGTEXT DEFAULT NULL,
          client_name VARCHAR(127) DEFAULT NULL,
          client_contact VARCHAR(127) DEFAULT NULL,
          client_lgu VARCHAR(127) DEFAULT NULL,
          request_date DATE DEFAULT NULL,
          control_number VARCHAR(63) DEFAULT NULL,
          verifier_name VARCHAR(127) DEFAULT NULL,
          verifier_position VARCHAR(63) DEFAULT NULL,
          STATUS VARCHAR(255) DEFAULT \'DRAFT\' NOT NULL,
          request_mode VARCHAR(255) DEFAULT \'ON_SITE\' NOT NULL,
          INDEX IDX_F4752EE8166D1F9C (project_id),
          INDEX IDX_F4752EE86C6B33F3 (performer_id),
          INDEX IDX_F4752EE8BB9D6FEE (issuer_id),
          INDEX IDX_F4752EE8BB23766C (approver_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_order_personnel (
          job_order_id INT NOT NULL,
          personnel_id INT NOT NULL,
          INDEX IDX_F1A7C749EAD8C843 (job_order_id),
          INDEX IDX_F1A7C7491C109075 (personnel_id),
          PRIMARY KEY(job_order_id, personnel_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnel (
          id INT AUTO_INCREMENT NOT NULL,
          project_id INT DEFAULT NULL,
          name VARCHAR(127) NOT NULL,
          position VARCHAR(63) NOT NULL,
          INDEX IDX_A6BCF3DE166D1F9C (project_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(127) NOT NULL,
          code VARCHAR(63) NOT NULL,
          logo VARCHAR(127) DEFAULT NULL,
          is_deleted TINYINT(1) DEFAULT 0 NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SESSION (
          id INT AUTO_INCREMENT NOT NULL,
          account_id INT NOT NULL,
          login DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
          logout DATETIME DEFAULT NULL,
          INDEX IDX_D044D5D49B6B5FBA (account_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          account
        ADD
          CONSTRAINT FK_7D3656A41C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE
          account
        ADD
          CONSTRAINT FK_7D3656A4BE8425AD FOREIGN KEY (current_session_id) REFERENCES SESSION (id)');
        $this->addSql('ALTER TABLE
          job_order
        ADD
          CONSTRAINT FK_F4752EE8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE
          job_order
        ADD
          CONSTRAINT FK_F4752EE86C6B33F3 FOREIGN KEY (performer_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE
          job_order
        ADD
          CONSTRAINT FK_F4752EE8BB9D6FEE FOREIGN KEY (issuer_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE
          job_order
        ADD
          CONSTRAINT FK_F4752EE8BB23766C FOREIGN KEY (approver_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE
          job_order_personnel
        ADD
          CONSTRAINT FK_F1A7C749EAD8C843 FOREIGN KEY (job_order_id) REFERENCES job_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          job_order_personnel
        ADD
          CONSTRAINT FK_F1A7C7491C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          personnel
        ADD
          CONSTRAINT FK_A6BCF3DE166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE
          SESSION
        ADD
          CONSTRAINT FK_D044D5D49B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A41C109075');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4BE8425AD');
        $this->addSql('ALTER TABLE job_order DROP FOREIGN KEY FK_F4752EE8166D1F9C');
        $this->addSql('ALTER TABLE job_order DROP FOREIGN KEY FK_F4752EE86C6B33F3');
        $this->addSql('ALTER TABLE job_order DROP FOREIGN KEY FK_F4752EE8BB9D6FEE');
        $this->addSql('ALTER TABLE job_order DROP FOREIGN KEY FK_F4752EE8BB23766C');
        $this->addSql('ALTER TABLE job_order_personnel DROP FOREIGN KEY FK_F1A7C749EAD8C843');
        $this->addSql('ALTER TABLE job_order_personnel DROP FOREIGN KEY FK_F1A7C7491C109075');
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DE166D1F9C');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D49B6B5FBA');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE job_order');
        $this->addSql('DROP TABLE job_order_personnel');
        $this->addSql('DROP TABLE personnel');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE session');
    }
}

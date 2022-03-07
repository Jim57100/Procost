<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304081939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employees (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, cost INT NOT NULL, hire_date DATE NOT NULL, INDEX IDX_BA82C300BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, sale_price INT DEFAULT NULL, start_date DATE NOT NULL, delivery_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE times (id INT AUTO_INCREMENT NOT NULL, employees_id INT DEFAULT NULL, projects_id INT DEFAULT NULL, times INT NOT NULL, INDEX IDX_1DD7EE8C8520A30B (employees_id), INDEX IDX_1DD7EE8C1EDE0F55 (projects_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300BE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE times ADD CONSTRAINT FK_1DD7EE8C8520A30B FOREIGN KEY (employees_id) REFERENCES employees (id)');
        $this->addSql('ALTER TABLE times ADD CONSTRAINT FK_1DD7EE8C1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE times DROP FOREIGN KEY FK_1DD7EE8C8520A30B');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C300BE04EA9');
        $this->addSql('ALTER TABLE times DROP FOREIGN KEY FK_1DD7EE8C1EDE0F55');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE times');
    }
}

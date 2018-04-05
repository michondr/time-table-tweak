<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180404200750 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, building VARCHAR(255) NOT NULL, room INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_table (id INT AUTO_INCREMENT NOT NULL, subject INT DEFAULT NULL, teacher INT DEFAULT NULL, location INT DEFAULT NULL, action_type ENUM(\'lecture\', \'seminar\', \'other\'), capacity_full INT DEFAULT NULL, capacity_class1 INT DEFAULT NULL, capacity_class2 INT DEFAULT NULL, capacity_class3 INT DEFAULT NULL, INDEX IDX_B35B6E3AFBCE3E7A (subject), INDEX IDX_B35B6E3AB0F6A6D5 (teacher), INDEX IDX_B35B6E3A5E9E89CB (location), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, indent VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE time_table ADD CONSTRAINT FK_B35B6E3AFBCE3E7A FOREIGN KEY (subject) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE time_table ADD CONSTRAINT FK_B35B6E3AB0F6A6D5 FOREIGN KEY (teacher) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE time_table ADD CONSTRAINT FK_B35B6E3A5E9E89CB FOREIGN KEY (location) REFERENCES location (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_table DROP FOREIGN KEY FK_B35B6E3A5E9E89CB');
        $this->addSql('ALTER TABLE time_table DROP FOREIGN KEY FK_B35B6E3AB0F6A6D5');
        $this->addSql('ALTER TABLE time_table DROP FOREIGN KEY FK_B35B6E3AFBCE3E7A');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE time_table');
        $this->addSql('DROP TABLE subject');
    }
}

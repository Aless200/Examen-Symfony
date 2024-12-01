<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129124036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C591CC992');
        $this->addSql('ALTER TABLE comment CHANGE course_id course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE course CHANGE name name VARCHAR(120) DEFAULT NULL, CHANGE small_description small_description LONGTEXT DEFAULT NULL, CHANGE full_description full_description LONGTEXT DEFAULT NULL, CHANGE duration duration VARCHAR(60) DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE program program VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C591CC992');
        $this->addSql('ALTER TABLE comment CHANGE course_id course_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE course CHANGE name name VARCHAR(120) NOT NULL, CHANGE small_description small_description LONGTEXT NOT NULL, CHANGE full_description full_description LONGTEXT NOT NULL, CHANGE duration duration VARCHAR(60) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE program program VARCHAR(255) NOT NULL');
    }
}

<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180912142716 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE repetition (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, space_override_id INT DEFAULT NULL, date DATETIME NOT NULL, time TINYINT(1) NOT NULL, end_date DATETIME DEFAULT NULL, duration NUMERIC(10, 2) DEFAULT NULL, extra LONGTEXT DEFAULT NULL, separate TINYINT(1) NOT NULL, INDEX IDX_9DB9AD5271F7E88B (event_id), INDEX IDX_9DB9AD521A51DFB3 (space_override_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE repetition ADD CONSTRAINT FK_9DB9AD5271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE repetition ADD CONSTRAINT FK_9DB9AD521A51DFB3 FOREIGN KEY (space_override_id) REFERENCES space (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE repetition');
    }
}

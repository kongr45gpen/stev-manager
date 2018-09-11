<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180911191855 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE volunteer (id INT AUTO_INCREMENT NOT NULL, instance_id INT NOT NULL, surname VARCHAR(255) NOT NULL, `name` VARCHAR(255) NOT NULL, father_name VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, property VARCHAR(255) DEFAULT NULL, school VARCHAR(255) DEFAULT NULL, `level` VARCHAR(255) DEFAULT NULL, health LONGTEXT DEFAULT NULL, interests LONGTEXT DEFAULT NULL, subscription TINYINT(1) NOT NULL, updates TINYINT(1) NOT NULL, joined TINYINT(1) NOT NULL, preparation TINYINT(1) NOT NULL, gender SMALLINT DEFAULT NULL, availability LONGTEXT NOT NULL, INDEX IDX_5140DEDB3A51721D (instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE submitter (id INT AUTO_INCREMENT NOT NULL, surname LONGTEXT NOT NULL, name LONGTEXT NOT NULL, email VARCHAR(255) DEFAULT NULL, property VARCHAR(255) DEFAULT NULL, faculty VARCHAR(255) DEFAULT NULL, school VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, phone_other VARCHAR(255) DEFAULT NULL, sector VARCHAR(255) DEFAULT NULL, lab VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, instance_id INT NOT NULL, space_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, team VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, scheduled SMALLINT NOT NULL, hidden TINYINT(1) NOT NULL, data LONGTEXT NOT NULL, INDEX IDX_3BAE0AA73A51721D (instance_id), INDEX IDX_3BAE0AA723575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_submitter (event_id INT NOT NULL, submitter_id INT NOT NULL, INDEX IDX_604766B671F7E88B (event_id), INDEX IDX_604766B6919E5513 (submitter_id), PRIMARY KEY(event_id, submitter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA73A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA723575340 FOREIGN KEY (space_id) REFERENCES space (id)');
        $this->addSql('ALTER TABLE event_submitter ADD CONSTRAINT FK_604766B671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_submitter ADD CONSTRAINT FK_604766B6919E5513 FOREIGN KEY (submitter_id) REFERENCES submitter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_submitter DROP FOREIGN KEY FK_604766B6919E5513');
        $this->addSql('ALTER TABLE event_submitter DROP FOREIGN KEY FK_604766B671F7E88B');
        $this->addSql('DROP TABLE volunteer');
        $this->addSql('DROP TABLE submitter');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_submitter');
    }
}

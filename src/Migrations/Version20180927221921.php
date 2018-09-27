<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180927221921 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE volunteer_availability (id INT AUTO_INCREMENT NOT NULL, volunteer_id INT NOT NULL, start DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\', end DATETIME DEFAULT NULL COMMENT \'(DC2Type:carbondatetime)\', INDEX IDX_1ED67FB88EFAB6B1 (volunteer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE volunteer_availability ADD CONSTRAINT FK_1ED67FB88EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE volunteer ADD shirt_size VARCHAR(24) DEFAULT NULL, ADD original_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD unique_id INT DEFAULT NULL, DROP availability');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE volunteer_availability');
        $this->addSql('ALTER TABLE volunteer ADD availability LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', DROP shirt_size, DROP original_data, DROP unique_id');
    }
}

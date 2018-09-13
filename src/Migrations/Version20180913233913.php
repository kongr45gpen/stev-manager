<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180913233913 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE instance ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\'');
        $this->addSql('ALTER TABLE volunteer ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\'');
        $this->addSql('ALTER TABLE space ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\'');
        $this->addSql('ALTER TABLE submitter ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\'');
        $this->addSql('ALTER TABLE event ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\'');
        $this->addSql('ALTER TABLE repetition ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:carbondatetime)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE instance DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE repetition DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE space DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE submitter DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE volunteer DROP created_at, DROP updated_at');
    }
}

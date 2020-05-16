<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200516111517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE messages ADD publicacion_id INT DEFAULT NULL, ADD visto TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id)');
        $this->addSql('CREATE INDEX IDX_DB021E969ACBB5E7 ON messages (publicacion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969ACBB5E7');
        $this->addSql('DROP INDEX IDX_DB021E969ACBB5E7 ON messages');
        $this->addSql('ALTER TABLE messages DROP publicacion_id, DROP visto');
    }
}

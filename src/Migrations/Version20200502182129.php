<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200502182129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE etiquetas (id INT AUTO_INCREMENT NOT NULL, etiqueta VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publicacion ADD etiqueta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085FD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiquetas (id)');
        $this->addSql('CREATE INDEX IDX_62F2085FD53DA3AB ON publicacion (etiqueta_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085FD53DA3AB');
        $this->addSql('DROP TABLE etiquetas');
        $this->addSql('DROP INDEX IDX_62F2085FD53DA3AB ON publicacion');
        $this->addSql('ALTER TABLE publicacion DROP etiqueta_id');
    }
}

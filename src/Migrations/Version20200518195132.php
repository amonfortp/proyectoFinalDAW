<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200518195132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE filtros (id INT AUTO_INCREMENT NOT NULL, provincia_id INT DEFAULT NULL, usuario_prop_id INT NOT NULL, orden_fecha VARCHAR(5) NOT NULL, tipo VARCHAR(255) NOT NULL, etiqueta VARCHAR(25) NOT NULL, INDEX IDX_9E72BE644E7121AF (provincia_id), INDEX IDX_9E72BE64A79555FA (usuario_prop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filtros ADD CONSTRAINT FK_9E72BE644E7121AF FOREIGN KEY (provincia_id) REFERENCES provincias (id)');
        $this->addSql('ALTER TABLE filtros ADD CONSTRAINT FK_9E72BE64A79555FA FOREIGN KEY (usuario_prop_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE filtros');
    }
}

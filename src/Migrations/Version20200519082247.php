<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519082247 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE filtros ADD etiqueta_id INT DEFAULT NULL, DROP tipo');
        $this->addSql('ALTER TABLE filtros ADD CONSTRAINT FK_9E72BE64D53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiquetas (id)');
        $this->addSql('CREATE INDEX IDX_9E72BE64D53DA3AB ON filtros (etiqueta_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE filtros DROP FOREIGN KEY FK_9E72BE64D53DA3AB');
        $this->addSql('DROP INDEX IDX_9E72BE64D53DA3AB ON filtros');
        $this->addSql('ALTER TABLE filtros ADD tipo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP etiqueta_id');
    }
}

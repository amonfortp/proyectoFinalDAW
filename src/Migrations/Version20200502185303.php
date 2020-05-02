<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200502185303 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE publicacion_etiquetas (publicacion_id INT NOT NULL, etiquetas_id INT NOT NULL, INDEX IDX_C253DA249ACBB5E7 (publicacion_id), INDEX IDX_C253DA24A6A0FAE6 (etiquetas_id), PRIMARY KEY(publicacion_id, etiquetas_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publicacion_etiquetas ADD CONSTRAINT FK_C253DA249ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publicacion_etiquetas ADD CONSTRAINT FK_C253DA24A6A0FAE6 FOREIGN KEY (etiquetas_id) REFERENCES etiquetas (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE publicacion_etiquetas');
    }
}

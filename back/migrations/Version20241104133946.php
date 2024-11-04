<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104133946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evento (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, recurrente TINYINT(1) NOT NULL, fecha DATE DEFAULT NULL, hora TIME NOT NULL, dias LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evento_equipo (evento_id INT NOT NULL, equipo_id INT NOT NULL, INDEX IDX_3D354E5A87A5F842 (evento_id), INDEX IDX_3D354E5A23BFBED (equipo_id), PRIMARY KEY(evento_id, equipo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evento_equipo ADD CONSTRAINT FK_3D354E5A87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evento_equipo ADD CONSTRAINT FK_3D354E5A23BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento_equipo DROP FOREIGN KEY FK_3D354E5A87A5F842');
        $this->addSql('ALTER TABLE evento_equipo DROP FOREIGN KEY FK_3D354E5A23BFBED');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE evento_equipo');
    }
}

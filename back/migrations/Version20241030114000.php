<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030114000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE concepto (id INT AUTO_INCREMENT NOT NULL, equipo_id INT NOT NULL, texto VARCHAR(255) NOT NULL, valor DOUBLE PRECISION NOT NULL, INDEX IDX_648388D023BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, genero VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, equipo_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, mote VARCHAR(255) NOT NULL, posicion VARCHAR(255) NOT NULL, dorsal INT DEFAULT NULL, rol VARCHAR(255) NOT NULL, INDEX IDX_527D6F1823BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE multa (id INT AUTO_INCREMENT NOT NULL, jugador_id INT NOT NULL, concepto_id INT NOT NULL, precio DOUBLE PRECISION NOT NULL, pagada TINYINT(1) NOT NULL, fecha_pagada DATE DEFAULT NULL, INDEX IDX_CB4ACB37B8A54D43 (jugador_id), INDEX IDX_CB4ACB376C2330BD (concepto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE concepto ADD CONSTRAINT FK_648388D023BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE jugador ADD CONSTRAINT FK_527D6F1823BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE multa ADD CONSTRAINT FK_CB4ACB37B8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE multa ADD CONSTRAINT FK_CB4ACB376C2330BD FOREIGN KEY (concepto_id) REFERENCES concepto (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE concepto DROP FOREIGN KEY FK_648388D023BFBED');
        $this->addSql('ALTER TABLE jugador DROP FOREIGN KEY FK_527D6F1823BFBED');
        $this->addSql('ALTER TABLE multa DROP FOREIGN KEY FK_CB4ACB37B8A54D43');
        $this->addSql('ALTER TABLE multa DROP FOREIGN KEY FK_CB4ACB376C2330BD');
        $this->addSql('DROP TABLE concepto');
        $this->addSql('DROP TABLE equipo');
        $this->addSql('DROP TABLE jugador');
        $this->addSql('DROP TABLE multa');
    }
}

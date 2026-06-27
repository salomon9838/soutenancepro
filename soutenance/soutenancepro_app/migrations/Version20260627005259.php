<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260627005259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE enseignant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE etudiant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE salle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE soutenance_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_user (id INT NOT NULL, enseignant_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom_complet VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9E7927C74 ON app_user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9E455FCC0 ON app_user (enseignant_id)');
        $this->addSql('CREATE TABLE enseignant (id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, specialite VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81A72FA1E7927C74 ON enseignant (email)');
        $this->addSql('CREATE TABLE etudiant (id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, filiere VARCHAR(100) NOT NULL, theme_memoire VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_717E22E3E7927C74 ON etudiant (email)');
        $this->addSql('CREATE TABLE salle (id INT NOT NULL, code VARCHAR(50) NOT NULL, capacite INT NOT NULL, localisation VARCHAR(150) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4E977E5C77153098 ON salle (code)');
        $this->addSql('CREATE TABLE soutenance (id INT NOT NULL, etudiant_id INT NOT NULL, president_id INT NOT NULL, rapporteur_id INT NOT NULL, examinateur_id INT NOT NULL, salle_id INT NOT NULL, date DATE NOT NULL, heure TIME(0) WITHOUT TIME ZONE NOT NULL, statut VARCHAR(20) DEFAULT \'programmee\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4D59FF6EDDEAB1A3 ON soutenance (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6EB40A33C7 ON soutenance (president_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6E2AF5D182 ON soutenance (rapporteur_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6E9D8D68C0 ON soutenance (examinateur_id)');
        $this->addSql('CREATE INDEX IDX_4D59FF6EDC304035 ON soutenance (salle_id)');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE soutenance ADD CONSTRAINT FK_4D59FF6EDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE soutenance ADD CONSTRAINT FK_4D59FF6EB40A33C7 FOREIGN KEY (president_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE soutenance ADD CONSTRAINT FK_4D59FF6E2AF5D182 FOREIGN KEY (rapporteur_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE soutenance ADD CONSTRAINT FK_4D59FF6E9D8D68C0 FOREIGN KEY (examinateur_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE soutenance ADD CONSTRAINT FK_4D59FF6EDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE enseignant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE etudiant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE salle_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE soutenance_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_user DROP CONSTRAINT FK_88BDF3E9E455FCC0');
        $this->addSql('ALTER TABLE soutenance DROP CONSTRAINT FK_4D59FF6EDDEAB1A3');
        $this->addSql('ALTER TABLE soutenance DROP CONSTRAINT FK_4D59FF6EB40A33C7');
        $this->addSql('ALTER TABLE soutenance DROP CONSTRAINT FK_4D59FF6E2AF5D182');
        $this->addSql('ALTER TABLE soutenance DROP CONSTRAINT FK_4D59FF6E9D8D68C0');
        $this->addSql('ALTER TABLE soutenance DROP CONSTRAINT FK_4D59FF6EDC304035');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE soutenance');
    }
}

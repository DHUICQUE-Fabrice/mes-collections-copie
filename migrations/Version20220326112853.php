<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220326112853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO object_family (code, label) VALUES ("PET", "Petshop")');
        $this->addSql('INSERT INTO object_family (code, label) VALUES ("HOR", "Cheval Schleich")');

        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Je ne sais pas")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Coccinelle")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Hérisson")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Papillon")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Poisson")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Chien")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Chat")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Lion")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Tortue")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Oiseau")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Lapin")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Chenille")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Lézard")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Grenouille")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Otarie")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Phoque")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Crocodile")');
        $this->addSql('INSERT INTO petshop_species (name) VALUES ("Serpent")');

        $this->addSql('INSERT INTO petshop_size (name) VALUES ("Je ne sais pas")');
        $this->addSql('INSERT INTO petshop_size (name) VALUES ("Teensie")');
        $this->addSql('INSERT INTO petshop_size (name) VALUES ("Mini")');
        $this->addSql('INSERT INTO petshop_size (name) VALUES ("Classique")');

        $this->addSql('INSERT INTO horse_species (name) VALUES ("Je ne sais pas")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Andalou")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Trakehnen")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Arabe")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Clydesdale")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Fjord")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Hahlinger")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Mustang")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Hanovre")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Islandais")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Knabstrupper")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Lipizzan")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Frison")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Pintabian")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Pinto")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Shire")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Tennesse Walker")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Tinker")');
        $this->addSql('INSERT INTO horse_species (name) VALUES ("Shetland")');

        $this->addSql('INSERT INTO horse_type (name) VALUES ("Je ne sais pas")');
        $this->addSql('INSERT INTO horse_type (name) VALUES ("Etalon")');
        $this->addSql('INSERT INTO horse_type (name) VALUES ("Poney")');
        $this->addSql('INSERT INTO horse_type (name) VALUES ("Jument")');
        $this->addSql('INSERT INTO horse_type (name) VALUES ("Poulain")');

        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Je ne sais pas")');
        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Isabelle")');
        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Bai")');
        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Gris")');
        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Alezan")');
        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Palomino")');
        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Noir")');
        $this->addSql('INSERT INTO horse_coat (name) VALUES ("Chocolat")');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

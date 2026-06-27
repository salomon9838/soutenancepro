<?php

namespace App\DataFixtures;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Salle;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Administrateur
        $admin = new User();
        $admin->setEmail('admin@soutenancepro.tg');
        $admin->setNomComplet('Administrateur Système');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Enseignants démo
        $enseignant1 = (new Enseignant())->setNom('KOFFI')->setPrenom('Jean')->setEmail('jean.koffi@univ.tg')->setSpecialite('Génie Logiciel');
        $enseignant2 = (new Enseignant())->setNom('AGBO')->setPrenom('Marie')->setEmail('marie.agbo@univ.tg')->setSpecialite('Réseaux');
        $enseignant3 = (new Enseignant())->setNom('DJOSSOU')->setPrenom('Paul')->setEmail('paul.djossou@univ.tg')->setSpecialite('Bases de données');
        $manager->persist($enseignant1);
        $manager->persist($enseignant2);
        $manager->persist($enseignant3);

        // Compte utilisateur pour l'enseignant 1
        $userEns = new User();
        $userEns->setEmail('jean.koffi@univ.tg');
        $userEns->setNomComplet('Jean KOFFI');
        $userEns->setRoles(['ROLE_ENSEIGNANT']);
        $userEns->setPassword($this->hasher->hashPassword($userEns, 'ens123'));
        $userEns->setEnseignant($enseignant1);
        $manager->persist($userEns);

        // Salles démo
        $salleA = (new Salle())->setCode('S101')->setCapacite(30)->setLocalisation('Bâtiment A, rez-de-chaussée');
        $salleB = (new Salle())->setCode('S202')->setCapacite(20)->setLocalisation('Bâtiment B, étage 1');
        $manager->persist($salleA);
        $manager->persist($salleB);

        // Étudiants démo
        $etu1 = (new Etudiant())->setNom('AMOUSSOU')->setPrenom('David')->setEmail('david.amoussou@etu.tg')->setFiliere('GL')->setThemeMemoire('Application de gestion des soutenances');
        $etu2 = (new Etudiant())->setNom('BAKOU')->setPrenom('Sandra')->setEmail('sandra.bakou@etu.tg')->setFiliere('WIM')->setThemeMemoire('Plateforme e-commerce');
        $manager->persist($etu1);
        $manager->persist($etu2);

        $manager->flush();
    }
}

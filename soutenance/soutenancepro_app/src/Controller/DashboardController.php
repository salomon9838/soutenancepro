<?php

namespace App\Controller;

use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\SalleRepository;
use App\Repository\SoutenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function admin(
        EtudiantRepository $etudiantRepository,
        EnseignantRepository $enseignantRepository,
        SalleRepository $salleRepository,
        SoutenanceRepository $soutenanceRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('dashboard/admin.html.twig', [
            'nbEtudiants' => count($etudiantRepository->findAll()),
            'nbEnseignants' => count($enseignantRepository->findAll()),
            'nbSalles' => count($salleRepository->findAll()),
            'nbSoutenances' => count($soutenanceRepository->findAll()),
        ]);
    }

    #[Route('/enseignant/dashboard', name: 'enseignant_dashboard')]
    public function enseignant(SoutenanceRepository $soutenanceRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENSEIGNANT');

        $user = $this->getUser();
        $enseignant = $user->getEnseignant();

        $soutenances = $enseignant ? $soutenanceRepository->findByEnseignant($enseignant) : [];

        return $this->render('dashboard/enseignant.html.twig', [
            'enseignant' => $enseignant,
            'soutenances' => $soutenances,
            'nbSoutenances' => count($soutenances),
        ]);
    }
}

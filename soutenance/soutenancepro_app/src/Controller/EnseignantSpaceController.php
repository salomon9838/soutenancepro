<?php

namespace App\Controller;

use App\Repository\SoutenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/enseignant')]
class EnseignantSpaceController extends AbstractController
{
    #[Route('/mes-soutenances', name: 'enseignant_soutenances')]
    public function mesSoutenances(SoutenanceRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENSEIGNANT');
        $enseignant = $this->getUser()->getEnseignant();
        $soutenances = $enseignant ? $repo->findByEnseignant($enseignant) : [];

        return $this->render('enseignant/mes_soutenances.html.twig', [
            'soutenances' => $soutenances,
        ]);
    }

    #[Route('/mes-jurys', name: 'enseignant_jurys')]
    public function mesJurys(SoutenanceRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENSEIGNANT');
        $enseignant = $this->getUser()->getEnseignant();
        $soutenances = $enseignant ? $repo->findByEnseignant($enseignant) : [];

        return $this->render('enseignant/mes_jurys.html.twig', [
            'soutenances' => $soutenances,
            'enseignant' => $enseignant,
        ]);
    }
}

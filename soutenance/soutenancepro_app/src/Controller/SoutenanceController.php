<?php

namespace App\Controller;

use App\Entity\Soutenance;
use App\Form\SoutenanceType;
use App\Repository\SoutenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/soutenances')]
class SoutenanceController extends AbstractController
{
    #[Route('/', name: 'soutenance_index', methods: ['GET'])]
    public function index(Request $request, SoutenanceRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $dateStr = $request->query->get('date');
        $date = $dateStr ? \DateTime::createFromFormat('Y-m-d', $dateStr) : null;

        return $this->render('soutenance/index.html.twig', [
            'soutenances' => $repo->searchByDate($date ?: null),
            'date' => $dateStr,
        ]);
    }

    #[Route('/nouveau', name: 'soutenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SoutenanceRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $soutenance = new Soutenance();
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $erreurs = $this->validerContraintes($soutenance, $repo, $em);
            if (!empty($erreurs)) {
                foreach ($erreurs as $erreur) {
                    $this->addFlash('error', $erreur);
                }
                return $this->render('soutenance/form.html.twig', ['form' => $form->createView(), 'titre' => 'Programmer une soutenance']);
            }

            $em->persist($soutenance);
            $em->flush();
            $this->addFlash('success', 'Soutenance programmée avec succès.');
            return $this->redirectToRoute('soutenance_index');
        }

        return $this->render('soutenance/form.html.twig', ['form' => $form->createView(), 'titre' => 'Programmer une soutenance']);
    }

    #[Route('/{id}/modifier', name: 'soutenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Soutenance $soutenance, EntityManagerInterface $em, SoutenanceRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $erreurs = $this->validerContraintes($soutenance, $repo, $em, $soutenance->getId());
            if (!empty($erreurs)) {
                foreach ($erreurs as $erreur) {
                    $this->addFlash('error', $erreur);
                }
                return $this->render('soutenance/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier la soutenance']);
            }

            $em->flush();
            $this->addFlash('success', 'Soutenance modifiée avec succès.');
            return $this->redirectToRoute('soutenance_index');
        }

        return $this->render('soutenance/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier la soutenance']);
    }

    #[Route('/{id}/annuler', name: 'soutenance_cancel', methods: ['POST'])]
    public function cancel(Request $request, Soutenance $soutenance, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('cancel'.$soutenance->getId(), $request->request->get('_token'))) {
            $soutenance->setStatut('annulee');
            $em->flush();
            $this->addFlash('success', 'Soutenance annulée.');
        }
        return $this->redirectToRoute('soutenance_index');
    }

    /**
     * Règles de gestion :
     * - un étudiant ne peut avoir qu'une seule soutenance (garanti par OneToOne)
     * - une salle ne peut pas accueillir deux soutenances au même moment
     * - un enseignant ne peut pas être dans deux jurys au même moment
     */
    private function validerContraintes(Soutenance $soutenance, SoutenanceRepository $repo, EntityManagerInterface $em, ?int $excludeId = null): array
    {
        $erreurs = [];
        $date = $soutenance->getDate();
        $heure = $soutenance->getHeure();
        $salle = $soutenance->getSalle();

        if ($date && $heure && $salle) {
            if ($repo->isSalleOccupee($salle->getId(), $date, $heure, $excludeId)) {
                $erreurs[] = sprintf('La salle %s est déjà occupée le %s à %s.', $salle->getCode(), $date->format('d/m/Y'), $heure->format('H:i'));
            }
        }

        if ($date && $heure) {
            $membres = [
                'Président' => $soutenance->getPresident(),
                'Rapporteur' => $soutenance->getRapporteur(),
                'Examinateur' => $soutenance->getExaminateur(),
            ];
            foreach ($membres as $role => $enseignant) {
                if ($enseignant && $repo->isEnseignantOccupe($enseignant->getId(), $date, $heure, $excludeId)) {
                    $erreurs[] = sprintf('%s (%s) participe déjà à un autre jury le %s à %s.', $enseignant, $role, $date->format('d/m/Y'), $heure->format('H:i'));
                }
            }

            $ids = array_filter([
                $soutenance->getPresident()?->getId(),
                $soutenance->getRapporteur()?->getId(),
                $soutenance->getExaminateur()?->getId(),
            ]);
            if (count($ids) !== count(array_unique($ids))) {
                $erreurs[] = 'Un même enseignant ne peut occuper deux rôles dans le même jury.';
            }
        }

        if ($soutenance->getEtudiant() && $soutenance->getEtudiant()->getSoutenance() && $soutenance->getEtudiant()->getSoutenance()->getId() !== $excludeId) {
            $erreurs[] = 'Cet étudiant possède déjà une soutenance programmée.';
        }

        return $erreurs;
    }
}

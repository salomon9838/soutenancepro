<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/etudiants')]
class EtudiantController extends AbstractController
{
    #[Route('/', name: 'etudiant_index', methods: ['GET'])]
    public function index(Request $request, EtudiantRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $nom = $request->query->get('nom');

        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $repo->searchByNom($nom),
            'nom' => $nom,
        ]);
    }

    #[Route('/nouveau', name: 'etudiant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($etudiant);
            $em->flush();
            $this->addFlash('success', 'Étudiant ajouté avec succès.');
            return $this->redirectToRoute('etudiant_index');
        }

        return $this->render('etudiant/form.html.twig', ['form' => $form->createView(), 'titre' => 'Ajouter un étudiant']);
    }

    #[Route('/{id}/modifier', name: 'etudiant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Étudiant modifié avec succès.');
            return $this->redirectToRoute('etudiant_index');
        }

        return $this->render('etudiant/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier l\'étudiant']);
    }

    #[Route('/{id}/supprimer', name: 'etudiant_delete', methods: ['POST'])]
    public function delete(Request $request, Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$etudiant->getId(), $request->request->get('_token'))) {
            $em->remove($etudiant);
            $em->flush();
            $this->addFlash('success', 'Étudiant supprimé.');
        }
        return $this->redirectToRoute('etudiant_index');
    }
}

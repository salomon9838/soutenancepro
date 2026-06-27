<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/enseignants')]
class EnseignantController extends AbstractController
{
    #[Route('/', name: 'enseignant_index', methods: ['GET'])]
    public function index(EnseignantRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('enseignant/index.html.twig', ['enseignants' => $repo->findAll()]);
    }

    #[Route('/nouveau', name: 'enseignant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $enseignant = new Enseignant();
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($enseignant);
            $em->flush();
            $this->addFlash('success', 'Enseignant ajouté avec succès.');
            return $this->redirectToRoute('enseignant_index');
        }

        return $this->render('enseignant/form.html.twig', ['form' => $form->createView(), 'titre' => 'Ajouter un enseignant']);
    }

    #[Route('/{id}/modifier', name: 'enseignant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enseignant $enseignant, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Enseignant modifié avec succès.');
            return $this->redirectToRoute('enseignant_index');
        }

        return $this->render('enseignant/form.html.twig', ['form' => $form->createView(), 'titre' => 'Modifier l\'enseignant']);
    }

    #[Route('/{id}/supprimer', name: 'enseignant_delete', methods: ['POST'])]
    public function delete(Request $request, Enseignant $enseignant, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$enseignant->getId(), $request->request->get('_token'))) {
            $em->remove($enseignant);
            $em->flush();
            $this->addFlash('success', 'Enseignant supprimé.');
        }
        return $this->redirectToRoute('enseignant_index');
    }
}

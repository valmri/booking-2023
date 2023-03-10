<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\Show;
use App\Form\ShowType;
use App\Repository\SeatRepository;
use App\Repository\ShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/show')]
class ShowController extends AbstractController
{
    #[Route('/', name: 'app_show_index', methods: ['GET'])]
    public function index(ShowRepository $showRepository, EntityManagerInterface $entityManager, SeatRepository $seatRepository): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        $shows = $showRepository->findRecentShow();

        $nb_places = $seatRepository->getNbPlaces();

        return $this->render('show/index.html.twig', [
            'shows' => $shows,
            'configuration' => $configuration,
            'nb_places' => $nb_places
        ]);
    }

    #[Route('/map', name: 'app_show_map', methods: ['GET'])]
    public function map(EntityManagerInterface $entityManager)
    {
        $configuration = $entityManager->find(Configuration::class, 1);
        return $this->render('show/map.html.twig', [
            'configuration' => $configuration
        ]);
    }

    #[Route('/new', name: 'app_show_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ShowRepository $showRepository, EntityManagerInterface $entityManager): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $affiche = $form->get('affiche')->getData();

            if ($affiche) {

                $affiche_nom = uniqid() . '.' . $affiche->guessExtension();

                $affiche->move(
                    $this->getParameter('affiches_directory'),
                    $affiche_nom
                );

                $show->setAffiche($affiche_nom);
            }

            $showRepository->save($show, true);

            return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('show/new.html.twig', [
            'show' => $show,
            'form' => $form,
            'configuration' => $configuration
        ]);
    }

    #[Route('/edit/{id}', name: 'app_show_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Show $show, ShowRepository $showRepository, EntityManagerInterface $entityManager): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $affiche = $form->get('affiche')->getData();

            if ($affiche) {

                $affiche_nom = uniqid() . '.' . $affiche->guessExtension();

                $affiche->move(
                    $this->getParameter('affiches_directory'),
                    $affiche_nom
                );

                $show->setAffiche($affiche_nom);
            }

            $showRepository->save($show, true);

            return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('show/edit.html.twig', [
            'show' => $show,
            'form' => $form,
            'configuration' => $configuration
        ]);
    }

    #[Route('/delete/{id}', name: 'app_show_delete', methods: ['POST'])]
    public function delete(Request $request, Show $show, ShowRepository $showRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$show->getId(), $request->request->get('_token'))) {
            $showRepository->remove($show, true);
        }

        return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
    }
}

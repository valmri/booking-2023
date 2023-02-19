<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\Show;
use App\Form\ShowType;
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
    public function index(ShowRepository $showRepository, EntityManagerInterface $entityManager): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        return $this->render('show/index.html.twig', [
            'shows' => $showRepository->findAll(),
            'configuration' => $configuration
        ]);
    }

    #[Route('/map', name: 'app_show_map', methods: ['GET'])]
    public function map()
    {
        return $this->render('show/map.html.twig');
    }

    #[Route('/new', name: 'app_show_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ShowRepository $showRepository, EntityManagerInterface $entityManager): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $showRepository->save($show, true);

            return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('show/new.html.twig', [
            'show' => $show,
            'form' => $form,
            'configuration' => $configuration
        ]);
    }

    #[Route('/{id}/edit', name: 'app_show_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Show $show, ShowRepository $showRepository): Response
    {
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $showRepository->save($show, true);

            return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('show/edit.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_show_delete', methods: ['POST'])]
    public function delete(Request $request, Show $show, ShowRepository $showRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$show->getId(), $request->request->get('_token'))) {
            $showRepository->remove($show, true);
        }

        return $this->redirectToRoute('app_show_index', [], Response::HTTP_SEE_OTHER);
    }
}

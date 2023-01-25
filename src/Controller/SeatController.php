<?php

namespace App\Controller;

use App\Entity\Seat;
use App\Form\GenerateSeatsFormType;
use App\Form\SeatType;
use App\Repository\SeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seat')]
class SeatController extends AbstractController
{
    #[Route('/', name: 'app_seat_index', methods: ['GET'])]
    public function index(): Response
    {
        $form = $this->createForm(GenerateSeatsFormType::class, [
            'rows' => 8,
            'seat_per_rows' => 8
        ]);

        // TODO: Traiter le formulaire

        return $this->render('seat/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

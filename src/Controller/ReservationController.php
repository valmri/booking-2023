<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\Reservation;
use App\Entity\Show;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation/new/{id}', name: 'app_reservation', methods: ['POST','GET'])]
    public function index(EntityManagerInterface $entityManager, Show $show, Request $request, ReservationRepository $reservationRepository): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);
        $user = $this->getUser();
        $est_reserve = $reservationRepository->isReserve((int)$user->getUserIdentifier(), (int)$show->getId());

        $reservation = new Reservation();
        $reservation->setDate(new \DateTime());
        $reservation->setSpectacle($show);
        $reservation->setUser($user);
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if(!$est_reserve) {
            if($form->isSubmitted()) {

                $reservationRepository->save($reservation, true);

            }

            $result = $this->render('reservation/new.html.twig', [
                'controller_name' => 'ReservationController',
                'configuration' =>$configuration,
                'form' => $form->createView()
            ]);
        } else {
            $result = $this->redirectToRoute('app_reservation_edit', [
                'id' => $show->getId()
            ]);
        }

        return $result;

    }

    #[Route('/reservation/edit/{id}', name: 'app_reservation_edit', methods: ['POST','GET'])]
    public function edit(EntityManagerInterface $entityManager, Show $show, Request $request, ReservationRepository $reservationRepository) {
        $configuration = $entityManager->find(Configuration::class, 1);
        $user = $this->getUser();

        $reservation = $reservationRepository->findOneBy([
            'user' => $user,
            'spectacle' => $show
        ]);
        $reservation->setDate(new \DateTime());

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $reservationRepository->save($reservation, true);
        }

        return $this->render('reservation/edit.html.twig', [
            'controller_name' => 'ReservationController',
            'configuration' =>$configuration,
            'form' => $form
        ]);
    }
}

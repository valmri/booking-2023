<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\Reservation;
use App\Entity\Show;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation/new/{id}', name: 'app_reservation', methods: ['POST','GET'])]
    public function index(EntityManagerInterface $entityManager, Show $show, Request $request, ReservationRepository $reservationRepository, UserRepository $userRepository): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);
        $user = $userRepository->findOneBy([
            'email' => $this->getUser()->getUserIdentifier()
        ]);

        $est_reserve = $reservationRepository->isReserve($user->getId(), (int)$show->getId());

        $erreur = null;

        $reservation = new Reservation();
        $reservation->setDate(new \DateTime());
        $reservation->setSpectacle($show);
        $reservation->setUser($user);
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if(!$est_reserve) {
            if($form->isSubmitted()) {

                $placeReservee = $reservationRepository->isPlaceReservee((int)$show->getId(), (int)$reservation->getSeat()->getId());

                if($placeReservee) {
                    $erreur = "Cette place a déjà été réservée.";
                } else {
                    $reservationRepository->save($reservation, true);
                }

            }

            $result = $this->render('reservation/new.html.twig', [
                'controller_name' => 'ReservationController',
                'configuration' =>$configuration,
                'form' => $form->createView(),
                'erreur' => $erreur
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
        $erreur = null;

        $reservation = $reservationRepository->findOneBy([
            'user' => $user,
            'spectacle' => $show
        ]);
        $reservation->setDate(new \DateTime());

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $placeReservee = $reservationRepository->isPlaceReservee((int)$show->getId(), (int)$reservation->getSeat()->getId());

            if($placeReservee) {
                $erreur = "Cette place a déjà été réservée.";
            } else {
                $reservationRepository->save($reservation, true);
            }

        }

        return $this->render('reservation/edit.html.twig', [
            'controller_name' => 'ReservationController',
            'configuration' =>$configuration,
            'form' => $form,
            'erreur' => $erreur
        ]);
    }
}

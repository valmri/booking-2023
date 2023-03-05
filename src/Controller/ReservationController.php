<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\Reservation;
use App\Entity\Show;
use App\Form\ReservationType;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation/new/{id}', name: 'app_reservation', methods: ['POST','GET'])]
    public function index(EntityManagerInterface $entityManager, Show $show): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        $reservation = new Reservation();
        $reservation->setDate(new \DateTime());
        $reservation->setSpectacle($show);
        $reservation->setUser($this->getUser());
        $form = $this->createForm(ReservationType::class, $reservation);

        return $this->render('reservation/new.html.twig', [
            'controller_name' => 'ReservationController',
            'configuration' =>$configuration,
            'form' => $form->createView()
        ]);
    }
}

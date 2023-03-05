<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Repository\SeatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager, SeatRepository $seatRepository): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        $nb_places = $seatRepository->getNbPlaces();

        return $this->render('default/index.html.twig', array(
            "configuration" => $configuration,
            "nbPlace" => $nb_places
        ));
    }
}

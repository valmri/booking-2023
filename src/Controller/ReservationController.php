<?php

namespace App\Controller;

use App\Entity\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation/new/{id}', name: 'app_reservation')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'configuration' =>$configuration
        ]);
    }
}

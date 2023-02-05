<?php

namespace App\Controller;

use App\Entity\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        return $this->render('default/index.html.twig', array(
            "configuration" => $configuration
        ));
    }
}

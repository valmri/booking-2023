<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Form\ConfigurationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationController extends AbstractController
{
    #[Route('/configuration', name: 'app_configuration')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);

        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->add('Enregistrer', SubmitType::class, [
            'attr' => ['class' => 'save']
        ]);

        $form->handleRequest($request);

        if($form->get('Enregistrer')->isClicked()) {
            $entityManager->persist($configuration);
            $entityManager->flush();
        }

        return $this->render('configuration/index.html.twig', [
            'controller_name' => 'ConfigurationController',
            'configuration' => $configuration,
            'form' => $form
        ]);
    }
}

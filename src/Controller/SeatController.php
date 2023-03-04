<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\Seat;
use App\Form\GenerateSeatsFormType;
use App\Form\SeatType;
use App\Repository\SeatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seat')]
class SeatController extends AbstractController
{
    #[Route('/', name: 'app_seat_index', methods: ['POST','GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, SeatRepository $seatRepository): Response
    {
        $configuration = $entityManager->find(Configuration::class, 1);
        $form = $this->createForm(GenerateSeatsFormType::class, [
            'rows' => 8,
            'seat_per_rows' => 8
        ]);
        $form->handleRequest($request);

        dump([0]);

        if($form->isSubmitted() && $form->isValid()) {

            // Récupération des données
            $nb_rangees = $form->get('rows')->getData();
            $nb_places_par_rangees = $form->get('seat_per_rows')->getData();

            // Génération des places
            $rangees = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

            // Faire une verification sur l'existance des places
            $derniereValeur = $seatRepository->findLastId();

            if($derniereValeur) {
                $derniereValeur = str_split($derniereValeur[0]->getName());

                $derniereRangee = $derniereValeur[0];
                $dernierNumPlace = (int)$derniereValeur[1];

                $derniereRangeeKey = array_search($derniereRangee, $rangees) + 1;

                if($nb_rangees > $derniereRangeeKey) {
                    for ($i = $derniereRangeeKey; $i < $nb_rangees; $i++) {
                        $str = '';
                        for ($j = 0; $j < $nb_places_par_rangees; $j++) {

                            $str = $rangees[$i];

                            $num_place = $j + 1;

                            $str .= $num_place;

                            $seat = new Seat();
                            $seat->setName($str);
                            $seatRepository->save($seat, true);

                        }
                    }
                }

                if($nb_places_par_rangees > $dernierNumPlace) {
                    for ($i = 0; $i < $nb_rangees; $i++) {
                        $str = '';
                        for ($j = $dernierNumPlace; $j < $nb_places_par_rangees; $j++) {

                            $str = $rangees[$i];

                            $num_place = $j + 1;

                            $str .= $num_place;

                            $seat = new Seat();
                            $seat->setName($str);
                            $seatRepository->save($seat, true);

                        }
                    }
                }


            }

        }

        return $this->render('seat/index.html.twig', [
            'form' => $form->createView(),
            'configuration' => $configuration
        ]);
    }
}

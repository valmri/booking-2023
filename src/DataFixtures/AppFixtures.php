<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Configuration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoryNames = [
            'Concert', 'Opéra', 'Ciné-concert', 'Danse', 'One-man show',
            'Pop', 'Rock', 'Jazz', 'Classique', 'Rap', 'Hip-hop', 'Insolite'
        ];

        foreach ($categoryNames as $categoryName) {
            $category = new Category;
            $category
                ->setName($categoryName);
            $manager->persist($category);
        }

        $configuration = new Configuration();
        $configuration->setNom('DAWIN-Arena')
            ->setAdresse('1337 rue de DAWIN')
            ->setCodePostal(33000)
            ->setVille('Bordeaux')
            ->setCarte('<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1889.7785023814422!2d-0.6119145469180434!3d44.79164068868266!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd54d8bd47723bfb%3A0x3d5d4b020fe7d55b!2s15%20Rue%20de%20Naudet%2C%2033170%20Gradignan!5e0!3m2!1sfr!2sfr!4v1676733591036!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
        $manager->persist($configuration);

        $manager->flush();
    }
}

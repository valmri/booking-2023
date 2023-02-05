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
            ->setCarte("https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1499.9454443822394!2d-0.6138282240539072!3d44.790595212516976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd54d8bcefa812c3%3A0x8a5fcb4ebd8a1df1!2sIUT%20de%20Bordeaux%20-%20Universit%C3%A9%20de%20Bordeaux!5e0!3m2!1sfr!2sfr!4v1675608275070!5m2!1sfr!2sfr");
        $manager->persist($configuration);

        $manager->flush();
    }
}

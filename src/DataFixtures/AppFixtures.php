<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Charge les données de séries et de saisons dans la bdd
 * À exécuter avec la commande :
 * php bin/console doctrine:fixtures:load
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //lit le fichier .sql qui est juste à côté
        $sql = file_get_contents(__DIR__ . "/datas.sql");

        //exécute la requête
        $manager->getConnection()->exec($sql);
    }
}

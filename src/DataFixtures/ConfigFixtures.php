<?php

namespace App\DataFixtures;

use App\Entity\SpaceTypes;
use App\Entity\SpaceAmenities;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createSpaceTypes($manager);
        $this->createAmenities($manager);
    }

    public function createAmenities(ObjectManager $manager)
    {
        $amenitiesData = [
            [
                'Surveillance Cameras', 
                'Caméras de surveillance',
            ],
            [
                'Lockers', 
                'Casiers',
            ],
            [
                'Heating', 
                'Chauffage',
            ],
            [
                'Access Key', 
                "Clé d’accès",
            ],
            [
                'Air Conditioning', 
                'Climatisation',
            ],
            [
                'Humidity Controller',
                'Contrôleur d’humidité',
            ],
            [
                'Dollies', 
                'Diables',
            ],
            [
                'Smoke Detector', 
                'Détecteur de fumée',
            ],
            [
                'Lighting', 
                'Éclairage',
            ],
            [
                'Large Entrance', 
                'Entrée de grande taille',
            ],
            [
                'Shelves', 
                'Étagères',
            ],
            [
                '24/7 Access',
                'Accès 24/7',
            ],
            [
                'Alarm',
                'Alarme',
            ],
            [
                'Storage Box',
                'Boîte de rangement',
            ],
            [
                'Storage Unit',
                'Caisson de rangement',
            ],
        ];
            

        foreach($amenitiesData as $data) {
            $amenity = new SpaceAmenities();
            $amenity->setNameEn($data[0]);
            $amenity->setNameFr($data[1]);
            $manager->persist($amenity);
            $manager->flush();
        }
    }

    public function createSpaceTypes(ObjectManager $manager)
    {
        $typesdata = [
            [
                'cellar', 
                'Cave',
            ],
            [
                'Commercial space', 
                'Espace commercial',
            ],
            [
                'Garden shed', 
                'Abri de jardin',
            ],
            [
                'Others', 
                'Autres',
            ],
            [
                'Storage unit', 
                'Unité de stockage',
            ],
            [
                'Warehouse', 
                'Entrepôt',
            ],
            [
                'Garage',
                'Garage',
            ],
        ];

        foreach($typesdata as $data) {
            $type = new SpaceTypes();
            $type->setNameEn($data[0]);
            $type->setNameFr($data[1]);
            $manager->persist($type);
            $manager->flush();
        }
    }
}

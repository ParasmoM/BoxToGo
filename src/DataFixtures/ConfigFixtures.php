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
            'Surveillance Cameras', 
            'Lockers', 
            'Heating', 
            'Access Key', 
            'Air Conditioning', 
            'Humidity Controller',
            'Dollies', 
            'Smoke Detector', 
            'Lighting', 
            'Large Entrance', 
            'Shelves', 
            '24/7 Access',
            'Alarm',
            'Storage Box',
            'Storage Unit'
        ];
            

        foreach($amenitiesData as $data) {
            $amenity = new SpaceAmenities();
            $amenity->setName($data);
            $manager->persist($amenity);
            $manager->flush();
        }
    }

    public function createSpaceTypes(ObjectManager $manager)
    {
        $typesdata = [
            'cellar', 
            'Commercial space', 
            'Garden shed', 
            'Others', 
            'Storage unit', 
            'Warehouse', 
            'Garage'
        ];

        foreach($typesdata as $data) {
            $type = new SpaceTypes();
            $type->setName($data);
            $manager->persist($type);
            $manager->flush();
        }
    }
}

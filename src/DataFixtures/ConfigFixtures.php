<?php

namespace App\DataFixtures;

use App\Entity\SpaceCategories;
use App\Entity\SpaceEquipements;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createCategory($manager);
        $this->createEquipments($manager);
    }

    public function createEquipments(ObjectManager $manager)
    {
        $datas = ['Surveillance Cameras', 'Lockers', 'Heating', 'Access Key', 'Air Conditioning', 'Humidity Controller', 'Dollies', 'Smoke Detector', 'Lighting', 'Large Entrance', 'Shelves', '24/7 Access'];

        foreach($datas as $data) {
            $equipment = new SpaceEquipements();
            $equipment->setName($data);
            $manager->persist($equipment);
            $manager->flush();
        }
    }

    public function createCategory(ObjectManager $manager)
    {
        $datas = ['cellar', 'Commercial space', 'Garden shed', 'Others', 'Storage unit', 'Warehouse', 'Garage'];

        foreach($datas as $data) {
            $categ = new SpaceCategories();
            $categ->setName($data);
            $manager->persist($categ);
            $manager->flush();
        }
    }
}

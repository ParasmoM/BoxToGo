<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Spaces;
use App\Entity\SpaceCategories;
use App\DataFixtures\HostFixtures;
use App\Entity\SpaceEquipementLink;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SpaceFixtures extends Fixture
{
    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder, 
    ) {
        $this->faker = $faker = Factory::create('fr_BE');
    }
    
    public function load(ObjectManager $manager): void
    {
        $this->createSpace($manager);
        $this->addItems($manager);
    }

    public function getDependencies()
    {
        return [
            HostFixtures::class,
        ];
    } 

    public function createSpace($manager)
    {
        $categData = $manager->getRepository(SpaceCategories::class)->findAll();
        $hostData = $manager->getRepository(Users::class)->findAll();

        for ($i = 0; $i < count($hostData); $i++) {
            $space = new Spaces();
            $space->setSpaceCateg($categData[$i]);
            $space->setPrice($this->faker->randomFloat(2, 10, 300)); 
            $space->setPrice($this->faker->randomFloat(2, 10, 300)); 
            $space->setSurface($this->faker->numberBetween(10, 200)); 
            $space->setEntryWidth($this->faker->randomFloat(2, 1, 5));
            $space->setEntryLength($this->faker->randomFloat(2, 1, 5)); 
            $space->setFloorPosition($this->faker->randomElement(['Rez de chaussée', 'Premier étage', 'Deuxième étage'])); 
            $space->setItemCondition($this->faker->randomElement(['Neuf', 'Rénové', 'À rénover', 'Ancien'])); 
            $space->setRegistrationDate($this->faker->dateTimeBetween('-10 years', 'now'));
            $space->setHost($hostData[$i]);

            $manager->persist($space);
            
            $hostData[$i]->addOwner($space);
        }
        $manager->flush();
    }

    public function addItems($manager)
    {
        $itemData = $manager->getRepository(SpaceEquipements::class)->findAll();
        $spaceData = $manager->getRepository(Spaces::class)->findAll();

        shuffle($itemData);  
        $randomItems = array_slice($itemData, 0, 7);  
        for ($i = 0; $i < count($spaceData); $i++) {

            $item = new SpaceEquipementLink();
            $item->addSpaceEquipment($randomItems[$i]);
            $item->setSpace($spaceData[$i]); 

            $manager->persist($item);

            $spaceData[$i]->addEquipment($item);
        }
        $manager->flush();
    }
}

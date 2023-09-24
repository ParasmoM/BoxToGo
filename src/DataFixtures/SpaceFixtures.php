<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Images;
use App\Entity\Spaces;
use DateTimeImmutable;
use App\Entity\Contents;
use App\Entity\Addresses;
use App\Entity\SpaceTypes;
use App\Entity\SpaceAmenities;
use App\Entity\SpaceAmenityLinks;
use App\DataFixtures\HostFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SpaceFixtures extends Fixture
{
    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder, 
    ) {
        $this->faker = Factory::create('fr_BE');
    }
    
    public function load(ObjectManager $manager): void
    {
        $this->createSpace($manager, 3);
        // $this->createSpace($manager);
        // $this->createSpace($manager);

        $this->addItems($manager);
        $this->addContents($manager);
        $this->addAdresse($manager);
        $this->addImage($manager);
    }

    public function getDependencies()
    {
        return [
            HostFixtures::class,
        ];
    } 

    public function createSpace($manager, $nbr)
    {
        $typeData = $manager->getRepository(SpaceTypes::class)->findAll();
        $hostData = $manager->getRepository(User::class)->findAll();
        $i = 0;

        for($index = 0; $index < $nbr; $index++) { 
            foreach ($hostData as $host) {
                $space = new Spaces();
                $space->setType($typeData[$i]);
                $space->setPrice($this->faker->randomFloat(2, 10, 300)); 
                $space->setPrice($this->faker->randomFloat(2, 10, 300)); 
                $space->setSurface($this->faker->numberBetween(10, 200)); 
                $space->setEntryWidth($this->faker->randomFloat(2, 1, 5));
                $space->setEntryLength($this->faker->randomFloat(2, 1, 5)); 
                $space->setFloorLevel($this->faker->randomElement(['Rez de chaussée', 'Premier étage', 'Deuxième étage'])); 
                $space->setConditionStatus($this->faker->randomElement(['Neuf', 'Rénové', 'À rénover', 'Ancien'])); 
                $space->setOwnedByUser($host);
    
                $dateTime = $this->faker->dateTimeBetween('-10 years', 'now');
                $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);
                $space->setCreateAt($dateTimeImmutable);
    
                $host->addOwner($space);
                $manager->persist($space);
                            
                $i++;
                if ($i == 7) {
                    $i = 0;
                }
            }
    
            $manager->flush();
        }
    }

    public function addItems($manager)
    {
        $itemData = $manager->getRepository(SpaceAmenities::class)->findAll();
        $spaceData = $manager->getRepository(Spaces::class)->findAll();

        for ($i = 0; $i < count($spaceData); $i++) {
            shuffle($itemData);  
            $randomItems = array_slice($itemData, 0, rand(1, 7));  

            foreach ($randomItems as $randomItem) {
                $item = new SpaceAmenityLinks();
                $item->addAmenity($randomItem);
                $item->setSpaces($spaceData[$i]);
        
                $manager->persist($item);
                $spaceData[$i]->addAmenty($item);
            }
            $manager->flush();
        }
    }

    public function addContents($manager)
    {
        $spaceData = $manager->getRepository(Spaces::class)->findAll();

        for ($i = 0; $i < count($spaceData); $i++) {

            $content = new Contents();
            $content->setTitleFr('Français' . $this->faker->sentences(1, true));
            $content->setTitleEn('Anglais' . $this->faker->sentences(1, true));
            $content->setDescriptionFr('Français' . $this->faker->sentences(10, true));
            $content->setDescriptionEn('Anglais' . $this->faker->sentences(10, true));

            $manager->persist($content);

            $spaceData[$i]->setContent($content);
        }
        $manager->flush();
    }

    public function addAdresse($manager)
    {
        $adresseData = [
            [
                'city' => 'Anvers',
                'zip' => 2000,
                'street' => 'Rue Meir',
                'number' => 1
            ],
            [
                'city' => 'Bruges',
                'zip' => 8000,
                'street' => 'Rue Steenstraat',
                'number' => 1
            ],
            [
                'city' => 'Bruxelles',
                'zip' => 1000,
                'street' => 'Rue de la Loi',
                'number' => 1
            ],
            [
                'city' => 'Charleroi',
                'zip' => 6000,
                'street' => 'Boulevard Tirou',
                'number' => 1
            ],
            [
                'city' => 'Gand',
                'zip' => 9000,
                'street' => 'Rue Veldstraat',
                'number' => 1
            ],
            [
                'city' => 'Liège',
                'zip' => 4000,
                'street' => 'Rue Saint-Gilles',
                'number' => 1
            ],
            [
                'city' => 'Namur',
                'zip' => 5000,
                'street' => 'Rue de Fer',
                'number' => 1
            ],
        ];

        $spacesData = $manager->getRepository(Spaces::class)->findAll();
        $i = 0;

        foreach ($spacesData as $space) {
            $adresse = new Addresses();
            $adresse->setCountry('Belgique');
            $adresse->setCity($adresseData[$i]['city']);
            $adresse->setStreet($adresseData[$i]['street']);
            $adresse->setStreetNumber($adresseData[$i]['number']);
            $adresse->setPostalCode($adresseData[$i]['zip']);
            
            $manager->persist($adresse);

            $space->setAdresse($adresse);     
            
            $i++;

            if ($i == 7) {
                $i = 0;
            }
        }
    
        $manager->flush();
    }

    public function addImage($manager)
    {
        $spacesData = $manager->getRepository(Spaces::class)->findAll();

        foreach ($spacesData as $space) {
            for ($i=1; $i < 6; $i++) { 

                $photo = new Images();                
                $photo->setSpaces($space);
                $photo->setImagePath($i. '.jpg');
                $photo->setSortOrder($i);
                
                $space->addImage($photo);
        
                $manager->persist($photo);
            }
        }
        $manager->flush();
    }
}

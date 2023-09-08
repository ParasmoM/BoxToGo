<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Spaces;
use App\Entity\Reviews;
use App\Entity\Adresses;
use App\Entity\Contents;
use App\Entity\SpaceImages;
use App\Entity\SpaceCategories;
use App\Entity\SpaceEquipements;
use App\Entity\SpaceEquipementLink;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\VarDumper\VarDumper;

class MyFixtures extends Fixture
{
    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder, 
    ) {
        $this->faker = $faker = Factory::create('fr_BE');
    }

    public function load(ObjectManager $manager): void
    {
        // $this->createCategory($manager);
        // $this->createEquipments($manager);

        // $this->createHosts(1, $manager);

        // for ($i = 1; $i < 11; $i++) {
        //     $this->createUser($manager);
        // }

        // $this->createReview($manager);
        // $manager->flush();
    }

    public function createReview(ObjectManager $manager)
    {
        $owners = [];
        $nonOwners = [];
        
        $allUsers = $manager->getRepository(Users::class)->findAll();

        foreach ($allUsers as $user) {
            $roles = $user->getRoles();  

            if (in_array('ROLE_OWNER', $roles)) {
                $owners[] = $user;  
            } else {
                $nonOwners[] = $user;  
            }
        }

        foreach ($owners as $host) {
            foreach ($host->getOwner() as $space) {
                foreach ($nonOwners as $user) {
                    $review = New Reviews();
                    $review->setComment($this->faker->sentences(5, true));
                    $review->setRating($this->faker->randomElement([1, 2, 3, 4, 5]));
                    $review->setUser($user);
                    $review->setSpace($space);
                    $manager->persist($review);
                    $space->addReview($review);
                }
            }
        }
        $manager->flush();
    }

    public function createUser(ObjectManager $manager)
    {
        $user = new Users();
        $user->setEmail($this->faker->email);
        $user->setPassword($this->passwordEncoder->hashPassword($user, 'azertyui') );
        $user->setGivenName($this->faker->firstName);
        $user->setFamilyName($this->faker->lastName);
        $user->setBirthDate($this->faker->dateTimeBetween('-40 years', '-18 years'));
        $user->setStatus('Particulier');
        $user->setGender($this->faker->randomElement(['Femme', 'Homme']));
        $user->setPhoneNumber($this->faker->numerify('04########'));
        $user->setRegistrationDate($this->faker->dateTimeBetween('-10 years', 'now'));

        $address = new Adresses();
        $address->setCountry('Belgique');
        $address->setCity($this->faker->randomElement(['Liège', 'Verviers', 'Seraing', 'Herstal', 'Ans', 'Grâce-Hollogne', 'Flémalle', 'Oupeye', 'Waremme', 'Saint-Nicolas']));
        $address->setStreet($this->faker->streetName);
        $address->setStreetNumber($this->faker->buildingNumber);
        $address->setPostalCode($this->faker->postcode);
        $manager->persist($address);
        $manager->flush();

        $user->setAdresse($address);
        $manager->persist($user);
        $manager->flush();
    }

    public function createHosts($id, ObjectManager $manager): void
    {
        $host = new Users();
        $host->setEmail($this->faker->email);
        $host->setPassword($this->passwordEncoder->hashPassword($host, 'azertyui') );
        $host->setRoles(['ROLE_USER', 'ROLE_OWNER']);
        $host->setGivenName($this->faker->firstName);
        $host->setFamilyName($this->faker->lastName);
        $host->setBirthDate($this->faker->dateTimeBetween('-40 years', '-18 years'));
        $host->setStatus($this->faker->randomElement(['Particulier', 'Professionnel']));
        $host->setGender($this->faker->randomElement(['Femme', 'Homme']));
        $host->setPhoneNumber($this->faker->numerify('04########'));
        $host->setRegistrationDate($this->faker->dateTimeBetween('-10 years', 'now'));

        $address = new Adresses();
        $address->setCountry('Belgique');
        $address->setCity($this->faker->randomElement(['Liège', 'Verviers', 'Seraing', 'Herstal', 'Ans', 'Grâce-Hollogne', 'Flémalle', 'Oupeye', 'Waremme', 'Saint-Nicolas']));
        $address->setStreet($this->faker->streetName);
        $address->setStreetNumber($this->faker->buildingNumber);
        $address->setPostalCode($this->faker->postcode);
        $manager->persist($address);
        $manager->flush();

        $host->setAdresse($address);
        $manager->persist($host);
        $manager->flush();

        $photo = new SpaceImages();
        $photo->setUser($host);
        $photo->setImagePath($host->getId(). '.jpeg');
        $photo->setSortOrder(21);
        $host->setProfilePicture($host->getId(). '.jpeg');
        $host->setImage($photo);
        $manager->persist($photo);
        $manager->flush();

        $content = new Contents();
        $content->setDescriptionFr('Français' . $this->faker->sentences(10, true));
        $content->setDescriptionEn('Anglais' . $this->faker->sentences(10, true));
        $content->setDescriptionNl('Néerlandais' . $this->faker->sentences(10, true));
        $host->setContent($content);
        $manager->persist($content);
        $manager->flush();

        $this->createSpace($host, $manager);
        $this->createSpace($host, $manager);
        $this->createSpace($host, $manager);
    }

    public function createSpace($host, ObjectManager $manager)
    {
        $dataCateg = $manager->getRepository(SpaceCategories::class)->findAll();
        $randomCateg = $this->faker->randomElement($dataCateg);

        $space = new Spaces();
        $space->setSpaceCateg($randomCateg);
        $space->setPrice($this->faker->randomFloat(2, 10, 300)); 
        $space->setSurface($this->faker->numberBetween(10, 200)); 
        $space->setEntryWidth($this->faker->randomFloat(2, 1, 5));
        $space->setEntryLength($this->faker->randomFloat(2, 1, 5)); 
        $space->setFloorPosition($this->faker->randomElement(['Rez de chaussée', 'Premier étage', 'Deuxième étage'])); 
        $space->setItemCondition($this->faker->randomElement(['Neuf', 'Rénové', 'À rénover', 'Ancien'])); 
        $space->setRegistrationDate($this->faker->dateTimeBetween('-10 years', 'now'));
        $space->setHost($host);
        $manager->persist($space);
        $manager->flush();

        $equipmentArray = $manager->getRepository(SpaceEquipements::class)->findAll();
        shuffle($equipmentArray);  
        $randomEquipment = array_slice($equipmentArray, 0, 7);  

        $equipmentLink = new SpaceEquipementLink();
        foreach ($randomEquipment as $equipment) {
            $equipmentLink->addSpaceEquipment($equipment);  
        }
        $equipmentLink->setSpace($space);  
        $manager->persist($equipmentLink);
        $manager->flush();

        $space->addEquipment($equipmentLink);

        $content = new Contents();
        $content->setTitleFr('Français' . $this->faker->sentences(1, true));
        $content->setTitleEn('Anglais' . $this->faker->sentences(1, true));
        $content->setTitleNl('Néerlandais' . $this->faker->sentences(1, true));
        $content->setDescriptionFr('Français' . $this->faker->sentences(5, true));
        $content->setDescriptionEn('Anglais' . $this->faker->sentences(5, true));
        $content->setDescriptionNl('Néerlandais' . $this->faker->sentences(5, true));
        $space->setContent($content);
        $manager->persist($content);
        $manager->flush();

        $address = new Adresses();
        $address->setCountry('Belgique');
        $address->setCity($this->faker->randomElement(['Liège', 'Verviers', 'Seraing', 'Herstal', 'Ans', 'Grâce-Hollogne', 'Flémalle', 'Oupeye', 'Waremme', 'Saint-Nicolas']));
        $address->setStreet($this->faker->streetName);
        $address->setStreetNumber($this->faker->buildingNumber);
        $address->setPostalCode($this->faker->postcode);
        $manager->persist($address);
        $manager->flush();

        $space->addAdresse($address);
        $manager->persist($space);
        $manager->flush();

        for($i = 1; $i < 11; $i++) {
            $photo = new SpaceImages();
            $photo->setSpace($space);
            $photo->setSortOrder($i);
            $photo->setImagePath($i . '.jpg');
            $manager->persist($photo);
            $space->addImage($photo);
            $manager->flush();
        }
        $manager->flush();
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

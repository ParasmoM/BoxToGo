<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Adresses;
use App\Entity\Contents;
use App\Entity\SpaceImages;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HostFixtures extends Fixture
{
    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder, 
    ) {
        $this->faker = $faker = Factory::create('fr_BE');
    }
    
    public function load(ObjectManager $manager): void
    {
        $this->createHost($manager);
        $this->addAdress($manager);
        $this->addImage($manager);
        $this->addDescription($manager);
    }

    public function getDependencies()
    {
        return [
            ConfigFixtures::class,
        ];
    } 

    public function createHost($manager)
    {
        $hostData = [
            [
                'firstName' => 'Idris',
                'lastName' => 'Alba',
                'gender' => 'Homme',
                'birthDate' => '6/09/1972',
            ],
            [
                'firstName' => 'Jasmine',
                'lastName' => 'Sanders',
                'gender' => 'Femme',
                'birthDate' => '22/06/1991',
            ],
            [
                'firstName' => 'Henry',
                'lastName' => 'Cavill',
                'gender' => 'Homme',
                'birthDate' => '5/05/1983',
            ],
            [
                'firstName' => 'Megan',
                'lastName' => 'Fox',
                'gender' => 'Femme',
                'birthDate' => '16/05/1986',
            ],
            [
                'firstName' => 'Will',
                'lastName' => 'Smith',
                'gender' => 'Homme',
                'birthDate' => '25/09/1968',
            ],
            [
                'firstName' => 'Zoe',
                'lastName' => 'Saldana',
                'gender' => 'Femme',
                'birthDate' => '19/05/1978',
            ],
            [
                'firstName' => 'Keanu',
                'lastName' => 'Reeves',
                'gender' => 'Homme',
                'birthDate' => '2/09/1964',
            ],
        ];
    
        foreach ($hostData as $data) {
            $host = new Users();
            $host->setEmail($this->faker->email); 
            $host->setPassword($this->passwordEncoder->hashPassword($host, 'azertyui'));
            $host->setRoles(['ROLE_USER', 'ROLE_OWNER']);
    
            
            $host->setGivenName($data['firstName']);
            $host->setFamilyName($data['lastName']);
            $host->setGender($data['gender']);
            
            
            $birthDate = \DateTime::createFromFormat('d/m/Y', $data['birthDate']);
            $host->setBirthDate($birthDate);
    
            $host->setStatus($this->faker->randomElement(['Particulier', 'Professionnel']));
            $host->setPhoneNumber($this->faker->numerify('04########'));
            $host->setRegistrationDate($this->faker->dateTimeBetween('-10 years', 'now'));
    
            $manager->persist($host);
        }
        $manager->flush();
    }
    
    public function addAdress($manager)
    {
        $adressData = [
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

        $hostData = $manager->getRepository(Users::class)->findAll();

        for ($i = 0; $i < count($hostData); $i++) {
            $address = new Adresses();
            $address->setCountry('Belgique');
            $address->setCity($adressData[$i]['city']);
            $address->setStreet($adressData[$i]['street']);
            $address->setStreetNumber($adressData[$i]['number']);
            $address->setPostalCode($adressData[$i]['zip']);
            
            $manager->persist($address);
            
            $hostData[$i]->setAdresse($address);
        }
    
        $manager->flush();
    }

    public function addImage($manager)
    {
        $hostData = $manager->getRepository(Users::class)->findAll();
    
        foreach ($hostData as $host) {
            $photo = new SpaceImages();
            
            $photo->setUser($host);
            $photo->setImagePath($host->getId(). '.jpeg');
            $photo->setSortOrder(21);
            
            $host->setProfilePicture($host->getId() . '.jpeg');
            $host->setImage($photo);
    
            $manager->persist($photo);
            $manager->persist($host);
        }
    
        $manager->flush();
    }
    
    public function addDescription($manager)
    {
        $hostData = $manager->getRepository(Users::class)->findAll();

        foreach ($hostData as $host) {
            $content = new Contents();

            $content->setDescriptionFr('Français' . $this->faker->sentences(10, true));
            $content->setDescriptionEn('Anglais' . $this->faker->sentences(10, true));
            $content->setDescriptionNl('Néerlandais' . $this->faker->sentences(10, true));

            $host->setContent($content);

            $manager->persist($content);
            $manager->persist($host);
        }

        $manager->flush();
    }

}

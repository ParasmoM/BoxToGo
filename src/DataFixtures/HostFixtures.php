<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Contents;
use App\Entity\Addresses;
use App\Entity\Images;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HostFixtures extends Fixture
{
    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder, 
    ) {
        $this->faker = Factory::create('fr_BE');
    }
    
    public function load(ObjectManager $manager): void
    {
        $this->createHost($manager);
        $this->addAdresse($manager);
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
                'firstName' => 'Gal',
                'lastName' => 'Gadot',
                'gender' => 'Femme',
                'birthDate' => '30/04/1985',
            ],
            [
                'firstName' => 'Joseph',
                'lastName' => 'Momoa',
                'gender' => 'Homme',
                'birthDate' => '1/08/1979',
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
            [
                'firstName' => 'Zazie',
                'lastName'  => 'Beetz',
                'gender'    => 'Femme',
                'birthDate' => '1/06/1991',
            ],
            [
                'firstName' => 'Michael B.',
                'lastName'  => 'Jordan',
                'gender'    => 'Homme',
                'birthDate' => '9/02/1987',
            ],
            [
                'firstName' => 'Meagan',
                'lastName'  => 'Good',
                'gender'    => 'Femme',
                'birthDate' => '8/08/1981',
            ],
            [
                'firstName' => 'Dwayne',
                'lastName'  => 'Johnson',
                'gender'    => 'Homme',
                'birthDate' => '2/05/1972',
            ],
            [
                'firstName' => 'Olivia',
                'lastName'  => 'Munn',
                'gender'    => 'Femme',
                'birthDate' => '3/07/1980',
            ],
            [
                'firstName' => 'Chris',
                'lastName'  => 'Hemsworth',
                'gender'    => 'Homme',
                'birthDate' => '11/08/1983',
            ],
            [
                'firstName' => 'Tessa',
                'lastName'  => 'Thompson',
                'gender'    => 'Femme',
                'birthDate' => '3/10/1983',
            ],
            [
                'firstName' => 'Donald',
                'lastName'  => 'Glover',
                'gender'    => 'Homme',
                'birthDate' => '25/09/1983',
            ],
            [
                'firstName' => 'Teyana',
                'lastName'  => 'Taylor',
                'gender'    => 'Femme',
                'birthDate' => '10/12/1990',
            ],
            [
                'firstName' => 'Ryan',
                'lastName'  => 'Gosling',
                'gender'    => 'Homme',
                'birthDate' => '12/11/1980',
            ],
            [
                'firstName' => 'Megan',
                'lastName'  => 'Fox',
                'gender'    => 'Femme',
                'birthDate' => '16/05/1986',
            ],
            [
                'firstName' => 'John',
                'lastName'  => 'Washington',
                'gender'    => 'Homme',
                'birthDate' => '28/07/1984',
            ],
            [
                'firstName' => 'Jamie',
                'lastName'  => 'Chung',
                'gender'    => 'Femme',
                'birthDate' => '10/04/1983',
            ],
            [
                'firstName' => 'Tom',
                'lastName'  => 'Hardy',
                'gender'    => 'Homme',
                'birthDate' => '15/09/1977',
            ],
            [
                'firstName' => 'Rihanna',
                'lastName'  => 'Fenty',
                'gender'    => 'Femme',
                'birthDate' => '20/02/1988',
            ],
            [
                'firstName' => 'Jamie',
                'lastName'  => 'Foxx',
                'gender'    => 'Homme',
                'birthDate' => '13/12/1967',
            ],

        ];
    
        foreach ($hostData as $data) {
            $host = new User();
            $host->setEmail($this->faker->email); 
            $host->setPassword($this->passwordEncoder->hashPassword($host, 'azertyui'));
            $host->setRoles(['ROLE_USER', 'ROLE_OWNER']);
    
            
            $host->setGivenName($data['firstName']);
            $host->setFamilyName($data['lastName']);
            $host->setGender($data['gender']);
            
            
            $birthDate = DateTimeImmutable::createFromFormat('d/m/Y', $data['birthDate']);
            $host->setBirthDate($birthDate);
    
            $host->setStatus($this->faker->randomElement(['Particulier', 'Professionnel']));
            $host->setPhoneNumber($this->faker->numerify('04########'));
            
            $dateTime = $this->faker->dateTimeBetween('-10 years', 'now');
            $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);
            $host->setCreateAt($dateTimeImmutable);
    
            $manager->persist($host);
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
        $hostData = $manager->getRepository(User::class)->findAll();
        $i = 0;

        foreach ($hostData as $host) {
            $adresse = new Addresses();
            $adresse->setCountry('Belgique');
            $adresse->setCity($adresseData[$i]['city']);
            $adresse->setStreet($adresseData[$i]['street']);
            $adresse->setStreetNumber($adresseData[$i]['number']);
            $adresse->setPostalCode($adresseData[$i]['zip']);
            
            $manager->persist($adresse);
            $host->setAdresse($adresse);
            
            $i++;
            if ($i == 7) {
                $i = 0;
            }
        }
    
        $manager->flush();
    }

    public function addImage($manager)
    {
        $hostData = $manager->getRepository(User::class)->findAll();
    
        foreach ($hostData as $host) {
            $photo = new Images();
            
            $photo->setUser($host);
            $photo->setImagePath($host->getId(). '.jpeg');
            $photo->setSortOrder(21);
            
            $host->setPicture($host->getId() . '.jpeg');
            $host->setImage($photo);
    
            $manager->persist($photo);
            $manager->persist($host);
        }
    
        $manager->flush();
    }
    
    public function addDescription($manager)
    {
        $hostData = $manager->getRepository(User::class)->findAll();

        foreach ($hostData as $host) {
            $content = new Contents();

            $content->setDescriptionFr('Français' . $this->faker->sentences(10, true));
            $content->setDescriptionEn('Anglais' . $this->faker->sentences(10, true));

            $host->setContent($content);

            $manager->persist($content);
            $manager->persist($host);
        }

        $manager->flush();
    }

}

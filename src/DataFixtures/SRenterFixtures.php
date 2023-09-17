<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Users;
use App\Entity\Spaces;
use DateTimeImmutable;
use App\Entity\Reviews;
use App\Entity\Adresses;
use App\Entity\Contents;
use App\Entity\Addresses;
use App\Entity\SpaceImages;
use App\Entity\Reservations;
use App\Entity\SpaceCategories;
use App\Entity\SpaceEquipements;
use App\Entity\SpaceEquipementLink;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\VarDumper\VarDumper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SRenterFixtures extends Fixture
{
    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder, 
    ) {
        $this->faker = $faker = Factory::create('fr_BE');
    }

    public function load(ObjectManager $manager): void
    {
        // for ($i = 1; $i < 11; $i++) {
        //     $this->createUser($manager);
        // }

        // $users = $this->separateOwnersAndNonOwners($manager);

        // $this->createResa($manager, $users['user']);
    }


    // public function createReview(ObjectManager $manager)
    // {
    //     foreach ($owners as $host) {
    //         foreach ($host->getOwner() as $space) {
    //             foreach ($nonOwners as $user) {
    //                 $review = New Reviews();
    //                 $review->setComment($this->faker->sentences(5, true));
    //                 $review->setRating($this->faker->randomElement([1, 2, 3, 4, 5]));
    //                 $review->setUser($user);
    //                 $review->setSpace($space);
    //                 $manager->persist($review);
    //                 $space->addReview($review);
    //             }
    //         }
    //     }
    //     $manager->flush();
    // }

    public function createResa($manager, $datas)
    {
        $spacesData = $manager->getRepository(Spaces::class)->findAll();

        foreach ($datas as $data) {
            foreach ($spacesData as $space) {
                $resa = new Reservations($space);

                // Générer la date d'hier
                $dateEnd = new DateTime('yesterday');
                
                // Générer la date de début avant la date de fin (utilisation de DateInterval)
                $interval = $this->faker->numberBetween(1, 30); // Nombre de jours à soustraire
                $dateStart = clone $dateEnd;
                $dateStart->sub(new DateInterval("P{$interval}D")); // Soustraire les jours
                
                // Générer la date de création avant la date de début
                $interval = $this->faker->numberBetween(1, $interval); // Nombre de jours à soustraire
                $createAt = clone $dateStart;
                $createAt->sub(new DateInterval("P{$interval}D")); // Soustraire les jours
                
                // Affecter les dates aux réservations
                $resa->setCreateAt($createAt);
                $resa->setDateStart($dateStart);
                $resa->setDateEnd($dateEnd);
                $resa->setStatus('finished');
                $resa->setUser($data);

                $manager->persist($resa);
            }
            $manager->flush();
        }
    }

    public function separateOwnersAndNonOwners($manager)
    {
        $owners = [];
        $nonOwners = [];
        
        $allUsers = $manager->getRepository(User::class)->findAll();

        foreach ($allUsers as $user) {
            $roles = $user->getRoles();  

            if (in_array('ROLE_OWNER', $roles)) {
                $owners[] = $user;  
            } else {
                $nonOwners[] = $user;  
            }
        }

        return $users = [
            'host' => $owners,
            'user' => $nonOwners
        ];
    }

    public function createUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail($this->faker->email);
        $user->setPassword($this->passwordEncoder->hashPassword($user, 'azertyui') );
        $user->setGivenName($this->faker->firstName);
        $user->setFamilyName($this->faker->lastName);
        $user->setBirthDate($this->faker->dateTimeBetween('-40 years', '-18 years'));
        $user->setStatus('Particulier');
        $user->setGender($this->faker->randomElement(['Femme', 'Homme']));
        $user->setPhoneNumber($this->faker->numerify('04########'));
        $user->setRoles(['ROLE_USER']);

        $dateTime = $this->faker->dateTimeBetween('-10 years', 'now');
        $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);
        $user->setCreateAt($dateTimeImmutable);

        $address = new Addresses();
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

}

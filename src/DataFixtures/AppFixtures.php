<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Receipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
     /**
     * 
     * @var Generator
     */
    private Generator $faker;

    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager) : void
    {
        $ingredients = array();
        for ($i=0; $i <=25 ; $i++) { 
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                       ->setPrice($this->faker->randomFloat(1, 1, 100));
                       $ingredients[]=$ingredient;
            $manager->persist($ingredient);
        }
        $receipe = new Receipe();
        for ($j=0; $j <=12 ; $j++) { 
            $receipe = new Receipe();
            $receipe->setName($this->faker->word())
                       ->setPrice($this->faker->randomFloat(1, 1, 100))
                       ->setTime(mt_rand(0,1) == 1 ? mt_rand(1,1440) : null)
                       ->setPeople(mt_rand(0,1) == 1 ? mt_rand(1,50) : null)
                       ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1,5) : null)
                       ->setDescription($this->faker->text(300))
                       ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1,1000) : null)
                       ->setIsfavorite(mt_rand(0,1) == 1 ? 1 : 0);
                       
            for ($k=0; $k < mt_rand(5,15); $k++) { 
                $receipe->addIngredient($ingredients[mt_rand(0,count($ingredients)-1)]);
            }
            $manager->persist($receipe);
        }

        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user->setFullName($this->faker->name())
                 ->setPseudo((mt_rand(0,1) == 1 ? $this->faker->firstName() : null))
                 ->setEmail($this->faker->email())
                 ->setRoles(["ROLE_USER"])
                 ->setPlainPassword("Password");
            $manager->persist($user);
        }
        $manager->flush();
    }


}

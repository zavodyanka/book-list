<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $category = [];
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->createCategory();

        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setName($this->faker->name);
            $book->setAuthor($this->faker->firstName . ' ' . $this->faker->lastName);
            $book->addCategory($this->category[$this->faker->numberBetween(0, 1)]);
            $book->addCategory($this->category[$this->faker->numberBetween(2, 3)]);

            $manager->persist($book);
        }

        $manager->flush();
    }

    private function createCategory()
    {
        $this->category[] = CategoryFactory::category()->setName("Animals");
        $this->category[] = CategoryFactory::category()->setName("Plants");
        $this->category[] = CategoryFactory::category()->setName("Economy");
        $this->category[] = CategoryFactory::category()->setName("Psychology");
    }
}

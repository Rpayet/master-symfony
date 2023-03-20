<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Fakecar;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR'); // Méthode static
        $faker->addProvider(new Fakecar($faker));

        $categories = [];
        
        $category = new Category();
        $category->setName('Smartphone');
        $manager->persist($category);
        $categories[] = $category;

        $category = new Category();
        $category->setName('Voiture');
        $manager->persist($category);
        $categories[] = $category;

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->vehicle());
            $product->setDescription($faker->text());
            $product->setPrice($faker->numberBetween(100, 2000) * 100);
            $product->setImage('https://picsum.photos/id/'.rand(0, 1000).'/200/200');
            $product->setCategory($categories[array_rand($categories)]);
            $manager->persist($product);
        }

        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setName($faker->name());
            $post->setContent($faker->text());
            $post->setPublishedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', '30 days')));
            $post->setActive((bool)random_int(0, 1));

            $manager->persist($post);
        }

        $manager->flush();
    }
}

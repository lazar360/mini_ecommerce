<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {

        $this->hasher = $hasher;

    }

    public function load(ObjectManager $manager): void
    {

        //Données sur product
        $product1 = new Product();
        $product1->setSku("ref1")
            ->setName ("Product1")
            ->setPrice(14)
            ->setImage("product01.png");
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setSku("ref2")
            ->setName ("Product2")
            ->setPrice(10)
            ->setImage("product02.png");
        $manager->persist($product2);


        $product3 = new Product();
        $product3->setSku("ref3")
            ->setName ("Product3")
            ->setPrice(15)
            ->setImage("product03.png");
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setSku("ref4")
            ->setName ("Product4")
            ->setPrice(20)
            ->setImage("product04.png");
        $manager->persist($product4);

        $product5 = new Product();
        $product5->setSku("ref5")
            ->setName ("Product5")
            ->setPrice(14)
            ->setImage("product05.png");
        $manager->persist($product5);

        $product6 = new Product();
        $product6->setSku("ref6")
            ->setName ("Product6")
            ->setPrice(20)
            ->setImage("product06.png");
        $manager->persist($product6);

        $product7 = new Product();
        $product7->setSku("ref7")
            ->setName ("Product7")
            ->setPrice(22)
            ->setImage("product07.png");
        $manager->persist($product7);

        $product8 = new Product();
        $product8->setSku("ref8")
            ->setName ("Product8")
            ->setPrice(18)
            ->setImage("product08.png");
        $manager->persist($product8);

        $product9 = new Product();
        $product9->setSku("ref9")
            ->setName ("Product9")
            ->setPrice(45)
            ->setImage("product09.png");
        $manager->persist($product9);

        //Création de l'admin
        $admin = new Customer();
        $password = $this->hasher->hashPassword($admin, "S3cr3T+");

        $admin ->setFirstname("admin")
            ->setLastname("admin")
            ->setLogin("admin")
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($password);
        $manager->persist($admin);

        $manager->flush();
    }
}

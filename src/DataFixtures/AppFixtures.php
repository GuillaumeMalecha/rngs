<?php

namespace App\DataFixtures;

use App\Cart\CartItem;
use App\Entity\Categorie;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\ProduitCommande;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Type;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $produits = [];
        $clients = [];

        $utilisateur = new Utilisateur();


        for ($i = 0; $i < 10; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail($faker->email);
            $utilisateur->setRoles(['ROLE_CLIENT']);
            $utilisateur->setNom($faker->lastName);
            $utilisateur->setPrenom($faker->firstName);
            $utilisateur->setNomutilisateur($faker->userName);
            $utilisateur->setTypeutilisateur('client');
            $utilisateur->setPassword($this->passwordHasher->hashPassword($utilisateur, 'password'));
            $manager->persist($utilisateur);
            $client = new Client();
            $client->setUtilisateur($utilisateur);
            $client->setTelephone($faker->phoneNumber);
            $client->setNewsletter($faker->boolean);
            $clients [] = $client;
            $manager->persist($client);
        }

        for ($i = 0; $i < 3; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($faker->word);
            $categorie->setDescription($faker->text);
            $manager->persist($categorie);

            for ($j = 0; $j < 10; $j++) {
                $produit = new Produit();
                $produit->setNom($faker->word);
                $produit->setDescription($faker->text);
                $produit->setPrix($faker->randomFloat(2, 0, 100) * 100);
                $produit->setDatesortie($faker->dateTime);
                $produit->setCategorie($categorie);
                $manager->persist($produit);
                $produits[] = $produit;
            }

        }
        $commande = new Commande();
        $commande->setDatecommande(new \DateTime('now'));
        $commande->setDatepaiement(new \DateTime('now'));
        $commande->setStatuspaiement('payé');
        $commande->setStatuscommande('en cours');
        $client = $faker->randomElement($clients);
        $commande->setClient($client);
        $manager->persist($commande);
        $panier = [new CartItem($faker->randomElement($produits), $faker->numberBetween(1, 10)), new CartItem($faker->randomElement($produits), $faker->numberBetween(1, 10))];

        foreach ($panier as $item) {
            $produitCommande = new ProduitCommande();
            $produitCommande->setProduit($item->getProduit());
            $produitCommande->setQuantite($item->getQuantite());
            $produitCommande->setCommande($commande);
            $produitCommande->setProduitNom($item->getProduit()->getNom());
            $produitCommande->setPrix($item->getProduit()->getPrix());
            $manager->persist($produitCommande);
        }
        //en vrai ce sera le client stocké dans l'utilisateur

        $manager->flush();
    }
}

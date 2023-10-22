<?php

namespace App\Service;

use App\Entity\Posts; // Importe la classe Posts
use Doctrine\ORM\EntityManagerInterface; // Importe l'interface EntityManagerInterface
use App\Entity\Post; // Importe la classe Post

class PostService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; // Injecte l'EntityManager dans la classe
    }

    public function createPost(array $postData): Posts
    {
        $post = new Posts(); // Crée une nouvelle instance de la classe Posts

        $post->setTitle($postData['title']); // Affecte le titre du post depuis les données fournies
        $post->setDescription($postData['description']); // Affecte la description du post depuis les données fournies

        $this->entityManager->persist($post); // Prépare l'entité à être persistée en base de données
        $this->entityManager->flush(); // Persiste réellement l'entité en base de données

        return $post; // Retourne l'entité de post créée
    }
}

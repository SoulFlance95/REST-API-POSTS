<?php

namespace App\Controller;

use App\Entity\Posts; // Importe la classe Posts
use App\Service\PostService; // Importe la classe PostService

use Doctrine\ORM\EntityManagerInterface; // Importe l'EntityManagerInterface de Doctrine
use Doctrine\Persistence\ManagerRegistry; // Importe le ManagerRegistry de Doctrine
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Importe la classe AbstractController de Symfony
use Symfony\Component\HttpFoundation\JsonResponse; // Importe la classe JsonResponse de Symfony
use Symfony\Component\HttpFoundation\Request; // Importe la classe Request de Symfony
use Symfony\Component\HttpFoundation\Response; // Importe la classe Response de Symfony
use Symfony\Component\Routing\Annotation\Route; // Importe la classe Route de Symfony

class PostsController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        // Cette méthode gère la page d'accueil, mais elle ne fait rien d'autre que de renvoyer une vue HTML.
        return $this->render('posts/index.html.twig', [
            'controller_name' => 'PostsController',
        ]);
    }

    private $postService; // Propriété pour stocker une instance de PostService
    private $entityManager; // Propriété pour stocker une instance de EntityManagerInterface

    public function __construct(PostService $postService, EntityManagerInterface $entityManager)
    {
        // Le constructeur initialise les dépendances du contrôleur.
        $this->postService = $postService; // Injecte le service de gestion de posts
        $this->entityManager = $entityManager; // Injecte l'EntityManager pour interagir avec la base de données
    }

    #[Route('/create-posts', name: 'app_posts')]
    public function create(ManagerRegistry $doctrine, Request $request)
    {
        // Cette méthode gère la création d'un nouveau post en utilisant les données fournies dans la requête.

        $datapost = new Posts(); // Crée une nouvelle instance de l'entité Posts
        $datapost->setTitle($request->get('title')); // Récupère le titre depuis la requête et l'assigne à l'entité
        $datapost->setDescription($request->get('description')); // Récupère la description depuis la requête et l'assigne à l'entité

        // Création d'un tableau de données à partir de l'entité
        $data = array(
            'id' => $datapost->getId(),
            'title' => $datapost->getTitle(),
            'description' => $datapost->getDescription(),
        );

        // Utiliser le service PostService pour créer le post en base de données
        $post = $this->postService->createPost($data);

        // Renvoyez les données du post créé sous forme de réponse JSON
        return $this->json([
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/posts', name: 'posts')]
    public function viewPosts(ManagerRegistry $doctrine)
    {
        // Cette méthode récupère tous les posts depuis la base de données et les renvoie sous forme de réponse JSON.

        $post = $doctrine->getRepository(Posts::class)->findAll(); // Récupère tous les posts
        $data = array();

        // Transformer les données des posts en un tableau de données
        foreach ($post as $pos) {
            $data[] = array(
                'id' => $pos->getId(),
                'title' => $pos->getTitle(),
                'description' => $pos->getDescription(),
            );
        }

        // Renvoyer les données des posts sous forme de réponse JSON
        return new JsonResponse($data, 200);
    }

    #[Route('/posts/{id}', name: 'post')]
    public function viewPost(ManagerRegistry $doctrine, int $id)
    {
        // Cette méthode récupère un post spécifique en fonction de l'ID fourni dans l'URL et le renvoie sous forme de réponse JSON.

        $post = $doctrine->getRepository(Posts::class)->find($id); // Récupère un post par son ID
        $data = array(
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
        );

        // Renvoyer les données du post sous forme de réponse JSON
        return new JsonResponse($data, 200);
    }
}
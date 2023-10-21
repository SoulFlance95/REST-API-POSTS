<?php

namespace App\Controller;

use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Posts;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{


    private $postService;


    public function __construct(PostService $postService)
    {
        $this->postService = $postService;

    }

    // Méthode pour créer un post basé sur les données de la requête


    #[Route('/create-posts', name: 'create_posts', methods: ['GET'])]
    public function createPost(Request $request)
    {

        // Décode les données JSON du contenu de la requête

        $data = json_decode($request->getContent(), true);

        if (empty($data['title']) || empty($data['description'])) {
            return new JsonResponse(array('status' => 'Vérifiez vos données', 404));

        }

        // Utilisez le PostService pour créer une entité de post
        $post = $this->postService->createPost($data);

        if ($post) {
        }

        // Renvoie une réponse si tout est OK

        return new Response('The post was created successfully');

    }


    #[Route('/posts', name: 'posts', methods: ['GET'])]
    public function viewAllPosts()
    {

        // Récupèrer tous les Posts
        $posts = $this->postService->getAllPosts();


        if (!$posts) {
            return new JsonResponse(array('status' => 'Aucun contenu n\'a été trouvé', 404));

        }

        // Renvoie une réponse JSON si tout est OK
        return new JsonResponse($posts, 200);


    }


    #[Route('/posts/{id}', name: 'app_posts', methods: ['GET'])]
    public function viewPost($id)
    {
        // Récupèrer un Post par ID

        $post = $this->postService->getPost($id);


        if (!$post) {
            return new JsonResponse(array('status' => 'Aucun contenu n\'a été trouvé', 404));

        }

        // Renvoie une réponse JSON si tout est OK
        return new JsonResponse($post, 200);



    }
}

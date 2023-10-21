<?php


// src/Service/PostService.php
namespace App\Service;

use App\Entity\Posts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



class PostService
{

    private $entityManager;
    private $mailer;


    // Constructeur pour injecter le EntityManager
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    public function sendEmail(Posts $posts)
    {

        // Implementation de la méthode pour l'envoi des mails

        $email = (new Email())
            ->from('sedare2017@gmail.com')
            ->to('team@devphantom.com')
            ->subject('Un nouveau post a été créé:')
            ->html('<p>Un nouveau post a été créé:</p><p>Titre: ' . $posts->getTitle() . '</p><p>Description: ' . $posts->getDescription() . '</p>');

        $this->mailer->send($email);
    }

    public function createPost(array $data): ?Posts
    {

        // Création d'une entité Post

        $posts = new Posts();
        $posts->setTitle($data['title'])
            ->setDescription($data['description']);


        // Sauvegarder l'entité dans la base de données

        $this->entityManager->persist($posts);
        $this->entityManager->flush();

        if ($this->entityManager->flush()) {
            $this->sendEmail($posts);
        }

        // Récupération des données de l'entité Post
        return $posts;


    }




    public function getPost($id)
    {
        // Récupère dune entité Post via l'ID du Post

        return $this->entityManager->getRepository(Posts::class)->find($id);
    }


    public function getAllPosts()
    {
        // Récupération de toutes les entités Post dans la base de données

        return $this->entityManager->getRepository(Posts::class)->findAll();

    }
}
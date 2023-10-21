<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use src\Controller\UserController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;



class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_registration', methods: 'GET')]

    public function register(Request $request, UserPasswordHasherInterface $passwordManager, JWTEncoderInterface $jWTEncoderInterface, PersistenceManagerRegistry $doctrine)
    {


        $data = json_decode($request->getContent(), true);

        // Vérifiez si les clés 'email' et 'mot de passe' sont présentes dans la BDD
        if (isset($data['email']) && isset($data['password'])) {
            $user = new User();

            // Définir l'email et le mot de passe de l'utilisateur
            $user->setEmail($data['email'])
                ->setPassword(sha1($data['password']));

            // Récupère le Doctrine Entity Manager
            $entityManager = $doctrine->getManager();

            // Conserver l'entité utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Encoder un jeton JWT avec les données utilisateur
            $token = $jWTEncoderInterface->encode(array($user));

            // Renvoie une réponse JSON contenant le jeton
            return new JsonResponse(array('token' => $token));

        } else {


            return new JsonResponse(null, 404);

        }

    }
}

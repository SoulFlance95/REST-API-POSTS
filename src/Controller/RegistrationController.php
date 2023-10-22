<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use src\Controller\UserController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class RegistrationController extends AbstractController
{



    #[Route('/api/register', name: 'user_registration')]

    public function register(Request $request, UserPasswordHasherInterface $passwordManager, JWTEncoderInterface $jWTEncoderInterface, TokenStorageInterface $tokenStorage, ManagerRegistry $doctrine)
    {

        $entityManager = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Vérifiez si les clés 'email' et 'mot de passe' sont présentes dans la BDD

        $user = new User();

        // Définir l'email et le mot de passe de l'utilisateur
        $user->setEmail($request->get('username'))
            ->setPassword(sha1($request->get('password')));

        $data = array(
            'username' => $user->getEmail(),
            'password' => $user->getPassword(),
        );


        // Conserver l'entité utilisateur dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();


        // Encodage du Token
        $token = $jWTEncoderInterface->encode($data);

        return new JsonResponse(['token' => $token]);



    }



}



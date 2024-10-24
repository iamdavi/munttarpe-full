<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    private EntityManagerInterface $em;

    private function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/register', name: 'api_register', methods: ['POST', 'GET'])]
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Email y contraseña son requeridos.'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($encoder->encodePassword($user, $data['password']));

        // Persistir el usuario
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(['message' => 'Usuario registrado con éxito.'], Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'api_login', methods: ['POST', 'GET'])]
    public function loginAction(UserInterface $user): JsonResponse
    {
        // Este controlador será manejado automáticamente por el sistema de seguridad
        return new JsonResponse(['message' => 'Login successful'], JsonResponse::HTTP_OK);
    }
}

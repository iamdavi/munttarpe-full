<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface  $passHash;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passHash
    ) {
        $this->em = $em;
        $this->passHash = $passHash;
    }

    #[Route('/register', name: 'api_auth_register', methods: ['POST', 'GET'])]
    public function register(Request $request): JsonResponse
    {
        list($email, $password) = $this->getEmailPassFromRequest($request);
        if ($email == false) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'msg' => 'Email y contraseña son requeridos.'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passHash->hashPassword($user, $password));

        // Persistir el usuario
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(
            [
                "status" => 'success',
                'msg' => 'Usuario registrado con éxito.'
            ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'api_auth_login', methods: ['POST', 'GET'])]
    public function loginAction(
        Request $request,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse {
        list($email, $password) = $this->getEmailPassFromRequest($request);
        if ($email == false) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'msg' => 'Email y contraseña son requeridos.'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$this->passHash->isPasswordValid($user, $password, null)) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'msg' => 'Credenciales inválidas'
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = $JWTManager->create($user);
        $refreshToken = bin2hex(random_bytes(32));

        $user->setRefreshToken($refreshToken);
        $this->em->persist($user);
        $this->em->flush();

        // Este controlador será manejado automáticamente por el sistema de seguridad
        return new JsonResponse([
            "status" => 'success',
            "token" => $token,
            "refreshToken" => $refreshToken
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/refresh', name: 'api_auth_refresh', methods: ['POST', 'GET'])]
    public function refreshAction(
        Request $request,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse {
        $refreshToken = $request->request->get('refreshToken');

        $user = $this->em->getRepository(User::class)->findOneBy(['refreshToken' => $refreshToken]);
        if (!$user) {
            return new JsonResponse(['message' => 'Invalid refresh token'], 401);
        }

        $newToken = $JWTManager->create($user);

        return new JsonResponse(['token' => $newToken]);
    }

    private function getEmailPassFromRequest(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return (isset($data['email']) && isset($data['password']))
            ? [$data['email'], $data['password']]
            : [false, false];
    }
}

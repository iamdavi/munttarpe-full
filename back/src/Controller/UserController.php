<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/user')]
class UserController extends AbstractController
{
    #[Route('/me', name: 'api_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var User */
        $user = $this->getUser();
        $data = $user->serialize();
        return new JsonResponse($data);
    }
}

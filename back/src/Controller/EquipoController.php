<?php

namespace App\Controller;

use App\Entity\Equipo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/equipo', name: "api_equipo_")]
class EquipoController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $equiposData = $this->em->getRepository(Equipo::class)->findAll();
        $equipos = array_map(fn(Equipo $equipo) => $equipo->serialize(), $equiposData);
        return new JsonResponse($equipos);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $equipo = new Equipo();
        $equipo->setNombre($data['nombre']);
        $equipo->setGenero($data['genero']);
        $equipo->setColor($data['color']);

        try {
            $this->em->persist($equipo);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'equipo' => $equipo->serialize()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST'])]
    public function edit(Request $request, Equipo $equipo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $field => $value) {
            $setter = Equipo::MAPPED_PROPERTIES_METHODS[$field] ?? false;
            if (!$setter) continue;
            $equipo->{Equipo::MAPPED_PROPERTIES_METHODS[$field]}($value);
        }

        try {
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'equipo' => $equipo->serialize()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Equipo $equipo): JsonResponse
    {
        try {
            $this->em->remove($equipo);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'msg' => 'Equipo eliminado'
        ]);
    }
}

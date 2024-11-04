<?php

namespace App\Controller;

use App\Entity\Jugador;
use App\Entity\Equipo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/jugador', name: "api_jugador_")]
class JugadorController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $jugadoresData = $this->em->getRepository(Jugador::class)->findAll();
        $jugadores = array_map(fn(Jugador $jugador) => $jugador->serialize(), $jugadoresData);
        return new JsonResponse($jugadores);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $equipo = $this->em->getRepository(Equipo::class)->find($data['equipo']['id']);

        $jugador = new Jugador();
        $jugador->setNombre($data['nombre']);
        $jugador->setApellidos($data['apellidos']);
        $jugador->setMote($data['mote']);
        $jugador->setPosicion($data['posicion']);
        $jugador->setDorsal($data['dorsal']);
        $jugador->setRol($data['rol']);
        $jugador->setEquipo($equipo);

        try {
            $this->em->persist($jugador);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'jugador' => $jugador->serialize()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST'])]
    public function edit(Request $request, Jugador $jugador): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $field => $value) {
            $setter = Jugador::MAPPED_PROPERTIES_METHODS[$field] ?? false;
            if (!$setter) continue;
            $jugador->{$setter}($value);
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
            'equipo' => $jugador->serialize()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Jugador $jugador): JsonResponse
    {
        try {
            $this->em->remove($jugador);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'msg' => 'Jugador eliminado'
        ]);
    }
}

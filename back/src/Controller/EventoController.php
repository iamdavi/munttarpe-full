<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Entity\Equipo;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/evento', name: "api_evento_")]
class EventoController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $eventosData = $this->em->getRepository(Evento::class)->findAll();
        $eventos = array_map(fn(Evento $evento) => $evento->serialize(), $eventosData);
        return new JsonResponse($eventos);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $equiposIds = array_map(fn(array $e) => $e['id'], $data['equipos']);
        $equipos = $this->em->getRepository(Equipo::class)->getEquiposByArrayIds($equiposIds);

        $fecha = new DateTime($data['fecha']);
        $hora = new DateTime($data['hora']);

        $evento = new Evento();
        $evento->setTipo($data['tipo']);
        $evento->setDescripcion($data['descripcion']);
        $evento->setRecurrente($data['recurrente']);
        $evento->setFecha($fecha);
        $evento->setHora($hora);
        $evento->setDias($data['dias']);

        foreach ($equipos as $equipo) {
            $equipo->addEvento($evento);
            $evento->addEquipo($equipo);
        }

        try {
            $this->em->persist($evento);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'evento' => $evento->serialize()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST'])]
    public function edit(Request $request, Evento $evento): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $field => $value) {
            $setter = Evento::MAPPED_PROPERTIES_METHODS[$field] ?? false;
            if (!$setter) continue;
            $evento->{$setter}($value);
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
            'equipo' => $evento->serialize()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Evento $evento): JsonResponse
    {
        try {
            $this->em->remove($evento);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'msg' => 'evento eliminado'
        ]);
    }
}

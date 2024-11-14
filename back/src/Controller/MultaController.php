<?php

namespace App\Controller;

use DateTime;
use App\Entity\Concepto;
use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\Multa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/multa', name: "api_multa_")]
class MultaController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $equipoId = $request->query->get('equipoId');
        $jugadoresData = $this->em->getRepository(Jugador::class)
            ->getJugadoresWithMultasByEquipo($equipoId);
        $jugadores = array_map(fn(Jugador $jugador) => $jugador->serialize(), $jugadoresData);
        return new JsonResponse($jugadores);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $jugador = $this->em->getRepository(Jugador::class)->find($data['jugador']['id']);
        $concepto = $this->em->getRepository(Concepto::class)->find($data['concepto']['id']);

        $fechaPagada = new DateTime($data['fechaPagada']);
        $fecha = new DateTime($data['fecha']);

        $multa = new Multa();
        $multa->setJugador($jugador);
        $multa->setConcepto($concepto);
        $multa->setPrecio($data['precio']);
        $multa->setPrecioPagado($data['precioPagado']);
        $multa->setPagada($data['pagada']);
        $multa->setDescripcion($data['descripcion']);
        $multa->setFechaPagada($fechaPagada);
        $multa->setFecha($fecha);

        try {
            $this->em->persist($multa);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'equipo' => $multa->serialize()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST'])]
    public function edit(Request $request, Equipo $equipo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $field => $value) {
            $setter = Equipo::MAPPED_PROPERTIES_METHODS[$field] ?? false;
            if (!$setter) continue;
            $equipo->{$setter}($value);
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

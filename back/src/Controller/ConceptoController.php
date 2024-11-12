<?php

namespace App\Controller;

use App\Entity\Concepto;
use App\Entity\Equipo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/concepto', name: "api_concepto_")]
class ConceptoController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list/{equipoId}', name: 'list', methods: ['GET'])]
    public function list(int $equipoId): JsonResponse
    {
        $conceptosData = $this->em->getRepository(Concepto::class)->findBy([
            'equipo' => $equipoId
        ]);
        $conceptos = array_map(fn(Concepto $concepto) => $concepto->serialize(), $conceptosData);
        return new JsonResponse($conceptos);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $equipo = $this->em->getRepository(Equipo::class)->find($data['equipo']['id']);

        $conceptoValor = (float) str_replace(',', '.', $data['valor']);

        $concepto = new Concepto();
        $concepto->setTexto($data['texto']);
        $concepto->setValor($conceptoValor);
        $concepto->setEquipo($equipo);

        try {
            $this->em->persist($concepto);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'concepto' => $concepto->serialize()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST'])]
    public function edit(Request $request, Concepto $concepto): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $field => $value) {
            $setter = Concepto::MAPPED_PROPERTIES_METHODS[$field] ?? false;
            if (!$setter) continue;
            $concepto->{$setter}($value);
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
            'concepto' => $concepto->serialize()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Concepto $concepto): JsonResponse
    {
        try {
            $this->em->remove($concepto);
            $this->em->flush();
        } catch (\Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'msg' => (string) $th
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'msg' => 'Concepto eliminado'
        ]);
    }
}

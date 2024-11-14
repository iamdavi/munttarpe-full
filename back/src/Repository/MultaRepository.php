<?php

namespace App\Repository;

use App\Entity\Multa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Multa>
 */
class MultaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Multa::class);
    }

    //    /**
    //     * @return Multa[] Returns an array of Multa objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Multa
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Método para obtener las multas que están relacionadas a un equipo en 
     * concreto
     * 
     * @param   int     $id     Id del Equipo::class del que obtener las multas
     * 
     * @return  array           Array de las Multas
     */
    public function getMultasByEquipo(int $id): array
    {
        $qb = $this->createQueryBuilder('m')
            ->innerJoin('m.jugador', 'j')
            ->innerJoin('j.equipo', 'e')
            ->where('e.id = :id')
            ->setParameter('id', $id);
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}

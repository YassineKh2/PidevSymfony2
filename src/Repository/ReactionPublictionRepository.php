<?php

namespace App\Repository;

use App\Entity\ReactionPubliction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReactionPubliction>
 *
 * @method ReactionPubliction|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReactionPubliction|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReactionPubliction[]    findAll()
 * @method ReactionPubliction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactionPublictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReactionPubliction::class);
    }

    public function save(ReactionPubliction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReactionPubliction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReactionPubliction[] Returns an array of ReactionPubliction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReactionPubliction
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function FindArticleByIdCategorie($id) {
        return $this->createQueryBuilder('c')
            ->join('c.Categorie','b')
            ->addSelect('b')
            ->where('b.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }
    public function Find10First() {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->setMaxResults(8)
            ->getResult();
    }
    public function FindArticle($name) {
        $em = $this->getEntityManager();

        $query =  $em->createQuery(
        'SELECT b
        FROM App\Entity\Article b
        WHERE b.NomArticle LIKE :name OR b.id LIKE :id'
        )->setParameter('name','%'.$name.'%')
         ->setParameter('id',$name);

      return $query->getResult();
    }

    public function FindBestALLTimeSellers(){
        return $this->createQueryBuilder('c')
            ->orderBy('c.SaleNumberArticle', 'DESC')
            ->getQuery()
            ->setMaxResults(8)
            ->getResult();
    }
    public function FilterbyPriceWithPrice($price,$id){
        return $this->createQueryBuilder('c')
            ->join('c.Categorie','b')
            ->addSelect('b')
            ->where('b.id=:id')
            ->setParameter('id',$id)
            ->andwhere('c.PrixArticle<=:PrixArticle')
            ->setParameter("PrixArticle",$price)
            ->getQuery()
            ->setMaxResults(8)
            ->getResult();
    }
    public function FilterbyPriceDown(){
        return $this->createQueryBuilder('c')
            ->orderBy('c.PrixArticle', 'DESC')
            ->getQuery()
            ->setMaxResults(8)
            ->getResult();
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}

<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }
    // Nouvelle méthode pour compter les livres par catégorie
    public function countBooksByCategory(string $category): int
{
    $qb = $this->createQueryBuilder('l')
        ->select('COUNT(l.ref)')
        ->where('l.category = :category')
        ->setParameter('category', $category);

    return (int) $qb->getQuery()->getSingleScalarResult();
}
public function findBooksBetweenDates(\DateTime $startDate, \DateTime $endDate)
{
    return $this->createQueryBuilder('l')
        ->where('l.publicationDate >= :startDate')
        ->andWhere('l.publicationDate <= :endDate')
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate)
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

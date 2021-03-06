<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getMostPopularCategories()
    {
        $temp = [];
        $data = $this->createQueryBuilder('c')
            ->select('COUNT(a) as articles, c.name')
            ->andWhere('a.status != TRUE')
            ->orderBy('articles', 'DESC')
            ->leftJoin('c.articles', 'a')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        foreach ($data as $d) {
            if ($d['name'] !== null) {
                $temp[] = $d;
            }
        }

        return $temp;
    }

    public function getCategoriesMatching(string $query)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :query')
            ->setParameter(":query", "%$query%")
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('c')
    ->andWhere('c.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('c.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
public function findOneBySomeField($value): ?Category
{
return $this->createQueryBuilder('c')
->andWhere('c.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}

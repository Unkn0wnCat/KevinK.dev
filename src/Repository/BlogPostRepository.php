<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * @param $maxPosts
     * @param $offset
     * @return BlogPost[]
     */
    public function findVisiblePosts($maxPosts, $offset)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.visible = true')
            ->orderBy('p.publishTime', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($maxPosts)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $maxPosts
     * @param $offset
     * @return BlogPost[]
     */
    public function findAllVisiblePosts()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.visible = true')
            ->orderBy('p.publishTime', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $category
     * @param $maxPosts
     * @param $offset
     * @return BlogPost[]
     */
    public function findVisiblePostsByCategory($category, $maxPosts, $offset)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.visible = true')
            ->andWhere('p.category = :category')
            ->orderBy('p.publishTime', 'ASC')
            ->setParameters([
                'category' => $category
            ])
            ->setFirstResult($offset)
            ->setMaxResults($maxPosts)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return BlogPost[] Returns an array of BlogPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogPost
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

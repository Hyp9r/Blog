<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function incrementViewCount(Post $post)
    {
        return $this->createQueryBuilder('p')
            ->update($this->getEntityName(), 'p')
            ->set('p.counter', $post->getCounter() + 1)
            ->where('p.id = :id')
            ->setParameter('id', $post->getId())
            ->getQuery()
            ->execute();
    }

    public function findAllFromLatestDate(){
        return $this->createQueryBuilder('post')->select('post')->orderBy('post.datePublished', 'DESC')->getQuery()->getResult();
    }

    public function findOneBySlug($slug): Post{
        return $this->createQueryBuilder('post')->select('post')
            ->where('post.slug = :slug')->setParameter('slug', $slug)->getQuery()->getSingleResult();
    }
}

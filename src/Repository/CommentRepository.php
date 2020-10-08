<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
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

    public function findById(int $id)
    {
        try {
            return $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.id = :val')
                ->setParameter('val', $id)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    public function incrementDownVotes(Comment $comment)
    {
        return $this->createQueryBuilder('comment')
            ->update($this->getEntityName(), 'comment')
            ->set('comment.down_votes', $comment->getDownVotes() + 1)
            ->where('comment.id = :id')
            ->setParameter('id', $comment->getId())
            ->getQuery()
            ->execute();
    }

    public function incrementUpVotes(Comment $comment)
    {
        return $this->createQueryBuilder('comment')
            ->update($this->getEntityName(), 'comment')
            ->set('comment.up_votes', $comment->getUpVotes() + 1)
            ->where('comment.id = :id')
            ->setParameter('id', $comment->getId())
            ->getQuery()
            ->execute();
    }

    /*
    public function findOneBySomeField($value): ?Comment
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

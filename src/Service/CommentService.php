<?php


namespace App\Service;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{

    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;


    /**
     * CommentService constructor.
     * @param CommentRepository $commentRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CommentRepository $commentRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }

    public function new(Comment $comment){
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function getComment(int $id){
        return $this->commentRepository->findById($id);
    }

    public function incrementUpVotes(Comment $comment){
        $this->commentRepository->incrementUpVotes($comment);
    }

    public function incrementDownVotes(Comment $comment){
        $this->commentRepository->incrementDownVotes($comment);
    }

}
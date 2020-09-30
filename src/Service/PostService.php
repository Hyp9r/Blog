<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class  PostService
{
    /**
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * PostService constructor.
     * @param PostRepository $postRepository
     * @param TagRepository $tagRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        PostRepository $postRepository,
        TagRepository $tagRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->entityManager = $entityManager;
    }

    public function createPost($data)
    {
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setText($data['text']);
        $post->setDatePublished($data['date']);
        $post->setSlug(data['slug']);
        $post->setCounter(data['counter']);
        $post->setUser($data['user']);
        $post->setVisible(true);

//        $post->setTags([$phptag, $dbtag]);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function new(Post $post){
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function updatePost(Post $post){
        $this->entityManager->persist($post);
        $this->entityManager->flush();;
    }

    public function getPosts(){
        return $this->postRepository->findAllFromLatestDate();
    }

    public function getPost(int $id){
        return $this->postRepository->find($id);
    }

    public function disablePost(int $id){
        $post = $this->postRepository->find($id);
        $post->setVisible(false);
        $this->entityManager->flush();
    }

    public function enablePost(int $id){
        $post = $this->postRepository->find($id);
        $post->setVisible(true);
        $this->entityManager->flush();
    }

    public function deletePost(int $id){
        $post = $this->postRepository->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

}

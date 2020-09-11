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
        $post->setCounter(data['counter']);
        $post->setSlug(data['slug']);

        $phptag = $this->tagRepository->findOneBy(['title' => 'PHP']);
        $dbtag = $this->tagRepository->findOneBy(['title' => 'Databases']);
        $post->setTags([$phptag, $dbtag]);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}

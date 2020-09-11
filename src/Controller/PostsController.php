<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\TagRepository;
use App\Service\PostService;
use http\Env\Request;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @var PostService
     */
    protected $postService;

    /**
     * PostsController constructor.
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(Request $request)
    {
        //Get all posts from DB
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        if (!$posts) {
            throw $this->createNotFoundException("No posts found");
            //throw new NotFoundHttpException('No posts found');
        };

        /**
         * @var $paginator Paginator
         */
        $paginator = $this->get('knp_paginator');
        $paginator->paginate(
            $posts,
            $request->posts->getInt('page', 1),
            $request->posts->getInt('limit', 2)
        );

        return $this->render('homepage.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route ("/posts/{slug}", name="app_post")
     */
    public function showPost($slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->findOneBySlug($slug);
        $entityManager->getRepository(Post::class)->incrementViewCount($post);
        $entityManager->flush();
        $tags = $post->getTags();
        return $this->render('post.html.twig', ['post' => $post, 'tags' => $tags]);
    }

    /**
     * @Route ("/create-post", name="app_create_post")
     */
    public function addPost(TagRepository $tagRepository)
    {
        $data = [
            'title' => '',
            'text' => '',
            'datePublished' => '',
            'counter' => '',
            'slug' => '',
        ];
        $this->postService->createPost($data);

        return new Response("Created object");
    }

}
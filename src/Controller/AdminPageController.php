<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PostService;
use Symfony\Component\Routing\Annotation\Route;

class AdminPageController extends AbstractController
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
     * @Route("/admin", name="app_admin")
     */
    public function showPosts()
    {
        $posts = $this->postService->getPosts();
        return $this->render('admin.html.twig', ['posts' => $posts]);
    }

}
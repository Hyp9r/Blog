<?php


namespace App\Controller;

use App\Service\PostService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @var PostService
     */
    protected $postService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * SearchController constructor.
     * @param PostService $postService
     * @param UserService $userService
     */
    public function __construct(PostService $postService, UserService $userService)
    {
        $this->postService = $postService;
        $this->userService = $userService;
    }

    /**
     * @Route ("/", name="app_search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $data = $request->query->get('search');
        $posts = $this->postService->search($data);
        return $this->render('search.html.twig', ['posts' => $posts]);
    }

}
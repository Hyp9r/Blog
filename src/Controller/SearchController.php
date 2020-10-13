<?php


namespace App\Controller;

use App\Service\HistoryService;
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
     * @var HistoryService
     */
    protected $historyService;

    /**
     * SearchController constructor.
     * @param PostService $postService
     * @param UserService $userService
     * @param HistoryService $historyService
     */
    public function __construct(PostService $postService, UserService $userService, HistoryService $historyService)
    {
        $this->postService = $postService;
        $this->userService = $userService;
        $this->historyService = $historyService;
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
        $searchTerm = [
            'user' => $this->getUser(),
            'search' => $data
        ];
        dd(111);
        addToUserHistory($searchTerm);
        dd(123);
        return $this->render('search.html.twig', ['posts' => $posts]);
    }

    private function addToUserHistory($data){
        dd(123);
        $this->historyService->addToUserHistory($data);
    }

}
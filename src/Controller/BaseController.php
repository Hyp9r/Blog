<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Service\HistoryService;
use App\Service\PostService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var Request
     */
    protected $request;

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
     * BaseController constructor.
     * @param Request $request
     * @param PostService $postService
     * @param UserService $userService
     * @param HistoryService $historyService
     */
    public function __construct(
        Request $request,
        PostService $postService,
        UserService $userService,
        HistoryService $historyService
    ) {
        $this->request = $request;
        $this->postService = $postService;
        $this->userService = $userService;
        $this->historyService = $historyService;
    }


    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($this->request);
        $popularUsers = $this->userService->getPopularUsers();
        $popularPosts = $this->postService->getMostViewedPosts();


        $histories = $this->historyService->getUserHistory($this->getUser());

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $this->request->get('search')['text'];
            $posts = $this->postService->searchPostsByKeyword($data);
            $postsByUser = $this->postService->searchPostsByUser($data);
            $users = $this->userService->search($data);
            if (!empty($posts) || !empty($users)) {
                return parent::render(
                    'search.html.twig',
                    [
                        'posts' => $posts,
                        'postsByUser' => $postsByUser,
                        'users' => $users,
                        'popularUsers' => $popularUsers,
                        'popularPosts' => $popularPosts,
                        'histories' => $histories,
                        'searchForm' => $searchForm->createView()
                    ]
                );
            }
        }

        $parameters = array_merge($parameters, ['histories' => $histories]);
        $parameters = array_merge($parameters, ['popularPosts' => $popularPosts]);
        $parameters = array_merge($parameters, ['popularUsers' => $popularUsers]);
        $parameters = array_merge($parameters, ['searchForm' => $searchForm->createView()]);

        return parent::render($view, $parameters, $response);
    }
}
<?php


namespace App\Controller;


use App\Service\HistoryService;
use App\Service\PostService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends BaseController
{

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @var HistoryService $historyService
     */
    protected $historyService;

    /**
     * @var PostService $postService
     */
    protected $postService;

    /**
     * UsersController constructor.
     * @param UserService $userService
     * @param RequestStack $requestStack
     * @param HistoryService $historyService
     * @param PostService $postService
     */
    public function __construct(
        UserService $userService,
        RequestStack $requestStack,
        HistoryService $historyService,
        PostService $postService
    ) {
        $this->userService = $userService;
        $this->request = $requestStack->getCurrentRequest();
        $this->historyService = $historyService;
        parent::__construct($this->request, $postService, $userService, $historyService);
    }

    /**
     * @Route("/profile/{id}", name="app_profile")
     * @param $id
     * @return Response
     */
    public function profile($id)
    {
        $user = $this->userService->getUser($id);
        return $this->render('profile.html.twig', ['user' => $user]);
    }

}
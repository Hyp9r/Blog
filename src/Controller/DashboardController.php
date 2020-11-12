<?php


namespace App\Controller;


use App\Service\HistoryService;
use App\Service\PostService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends BaseController
{
    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @var PostService $postService
     */
    protected $postService;

    /**
     * @var HistoryService $historyService
     */
    protected $historyService;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * RegisterController constructor.
     * @param RequestStack $request
     * @param PostService $postService
     * @param UserService $userService
     * @param HistoryService $historyService
     */
    public function __construct(
        RequestStack $request,
        PostService $postService,
        UserService $userService,
        HistoryService $historyService
    ) {
        $this->userService = $userService;
        $this->request = $request->getCurrentRequest();
        $this->postService = $postService;
        $this->historyService = $historyService;
        parent::__construct($this->request, $this->postService, $this->userService, $this->historyService);
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard()
    {

        return $this->render('dashboard/dashboard.html.twig', []);
    }


}
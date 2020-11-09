<?php


namespace App\Controller;


use App\Form\FollowType;
use App\Service\HistoryService;
use App\Service\PostService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowController extends BaseController
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
     * @Route("/follow", name="app_follow", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function follow(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $user = $this->userService->getUser($this->getUser());

            $targetUser = $this->userService->getUser($request->get('targetUser'));
            $this->userService->followUser($user, $targetUser);
            return new Response("Followed user: " . $targetUser->getId());
        }
    }


    /**
     * @Route("/unfollow", name="app_unfollow", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function unfollow(Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->userService->getUser($this->getUser());

            $targetUser = $this->userService->getUser($request->get('targetUser'));
            $this->userService->unFollowUser($user, $targetUser);
            return new Response("Unfollowed user: " . $targetUser->getId());
        }
    }

}
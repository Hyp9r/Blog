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
     * @param Request $request
     * @return Response
     */
    public function profile($id, Request $request)
    {
        $alreadyFollowing = false;


        //variable that helps us for checking if loggedIn user follows target user
        $currentUser = $this->getUser();

        $user = $this->userService->getUser($id);

        $usersAlreadyFollowing = $user->getFollowers();

        if($usersAlreadyFollowing->contains($currentUser)){
            //user is already following target user
            $alreadyFollowing = true;
        }


        $followers = $this->userService->getFollowers($user);//this returns users that I follow
        $following = $this->userService->getFollowings($user);//this returns users that follow me
        $posts = $this->postService->getPostsFromUser($id);
        $numberOfFollowers = count($followers);
        $numberOfFollowings = count($following);
        $numberOfPosts = count($posts);

        return $this->render(
            'profile.html.twig',
            [
                'following' => $alreadyFollowing,
                'user' => $user,
                'numberOfPosts' => $numberOfPosts,
                'followers' => $followers,
                'numberOfFollowings' => $numberOfFollowings,
                'numberOfFollowers' => $numberOfFollowers,
                'posts' => $posts
            ]
        );
    }

}
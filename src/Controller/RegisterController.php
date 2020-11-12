<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\HistoryService;
use App\Service\PostService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends BaseController
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
     * @Route ("/register", name="app_register")
     * @param Request $request
     * @return Response
     */
    public function registerUser(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->createUser($user);

            $this->addFlash('success', 'user_created_successfully');

            $this->redirectToRoute('app_login');
        }

        return $this->render('register.html.twig', ['form' => $form->createView()]);
    }
}
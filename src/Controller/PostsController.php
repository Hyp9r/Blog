<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Service\CommentService;
use App\Service\PostService;
use App\Service\UserService;
use ContainerRTFgIzf\getKnpPaginatorService;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PostsController extends BaseController
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
     * @var CommentService
     */
    protected $commentService;

    /**
     * @var Request
     */
    protected $request;

    private $security;

    /**
     * PostsController constructor.
     * @param PostService $postService
     * @param Security $security
     * @param CommentService $commentService
     * @param UserService $userService
     * @param RequestStack $request
     */
    public function __construct(
        PostService $postService,
        Security $security,
        CommentService $commentService,
        UserService $userService,
        RequestStack $requestStack
    ) {
        $this->postService = $postService;
        $this->security = $security;
        $this->commentService = $commentService;
        $this->userService = $userService;
        $this->request = $requestStack->getCurrentRequest();
        parent::__construct($this->request, $postService, $userService);
    }

    /**
     * @Route("/", name="app_homepage")
     * @param PostRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function homepage(PostRepository $repository, PaginatorInterface $paginator)
    {
//        $form = $this->createForm(SearchType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
////            dd($request);
//
//            $data = $request->get('search')['text'];
//            $posts = $this->postService->search($data);
////            $users = $this->userService->search($data);
//            return $this->render('search.html.twig', ['posts' => $posts, 'form' => $form->createView()]);
//        }

        //Depending on user role, fetch only visible posts, or all if user is admin
        $data = null;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $data = $repository->findAllFromLatestDate();
        } else {
            $data = $repository->findAllVisible();
        }

        /**
         * @var $paginator Paginator
         */
        $pagination = $paginator->paginate(
            $data,
            $this->request->query->getInt('page', 1),
            $this->request->query->getInt('limit', 6)
        );


        return $this->render('homepage.html.twig', ['paginatedPosts' => $pagination]);
    }

    /**
     * @Route ("/posts/{slug}", name="app_post")
     * @param $slug
     * @param Request $request
     * @return Response
     */
    public function showPost($slug, Request $request)
    {
        $post = $this->postService->getPostBySlug($slug);
        $tags = $post->getTags();

        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');

        $comment = new Comment();
        $comment->setUpVotes(0);
        $comment->setDownVotes(0);
        $comment->setDatePublished($date);
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('ROLE_USER')) {
            $comment->setUser($this->getUser());

            $this->commentService->new($comment);
            $this->addFlash('success', 'comment_created_successfully');
            return $this->redirectToRoute("app_homepage");
        } else {
            //either the form isn't valid, or user isn't logged in
        }

        return $this->render('post.html.twig', ['post' => $post, 'tags' => $tags, 'form' => $form->createView()]);
    }

    /**
     * @Route ("/create-post", name="app_create_post")
     * @param TagRepository $tagRepository
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createPost(TagRepository $tagRepository, Request $request)
    {
        $post = new Post();
        $post->setUser($this->getUser());
        $post->setVisible(true);
        $post->setCounter(0);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $date->format('Y-m-d H:i:s');
            $post->setDatePublished($date);
            $this->postService->new($post);

            $this->addFlash('success', 'post.created_successfully');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('create-update-post.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route ("/update-post/{id}", name="app_update_post")
     * @param int $id
     * @return Response
     */
    public function updatePost(Request $request, int $id)
    {
        $post = $this->postService->getPost($id);

        $form = $this->createForm(PostType::class, $post);

        $form->get('title')->setData($post->getTitle());
        $form->get('text')->setData($post->getText());
        $form->get('tags')->setData($post->getTags());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->updatePost($post);

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('app_homepage');
        }
        return $this->render('create-update-post.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route ("/disable-post/{id}", name="app_disable_post")
     * @param int $id
     * @return RedirectResponse
     */
    public function disablePost(int $id)
    {
        $this->postService->disablePost($id);
        return new RedirectResponse("/");
    }

    /**
     * @Route ("/enable-post/{id}", name="app_enable_post")
     * @param int $id
     * @return RedirectResponse
     */
    public function enablePost(int $id)
    {
        $this->postService->enablePost($id);
        return new RedirectResponse("/");
    }

    /**
     * @Route ("/delete-post/{id}", name="app_delete_post")
     * @param int $id
     * @return RedirectResponse
     */
    public function deletePost(int $id)
    {
        $this->postService->deletePost($id);
        return new RedirectResponse("/");
    }

    /**
     * @Route ("/posts/{slug}/upvote/{comment_id}", name="app_upvote_post")
     * @param int $comment_id
     * @param string $slug
     * @return RedirectResponse
     */
    public function upvote(int $comment_id, string $slug)
    {
        $comment = $this->commentService->getComment($comment_id);
        $this->commentService->incrementUpVotes($comment);
        return new RedirectResponse("/posts/{$slug}");
    }

    /**
     * @Route ("/posts/{slug}/downvote/{comment_id}", name="app_downvote_post")
     * @param int $comment_id
     * @param string $slug
     * @return RedirectResponse
     */
    public function downVote(int $comment_id, string $slug)
    {
        $comment = $this->commentService->getComment($comment_id);
        $this->commentService->incrementDownVotes($comment);
        return new RedirectResponse("/posts/{$slug}");
    }


}
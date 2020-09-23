<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Service\PostService;
use ContainerRTFgIzf\getKnpPaginatorService;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
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
     * @Route("/", name="app_homepage")
     * @param PostRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function homepage(PostRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        /**
         * @var $paginator Paginator
         */
        $pagination = $paginator->paginate(
            $repository->findAllFromLatestDate(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 2)
        );

        $user = null;

        return $this->render('homepage.html.twig', ['posts' => $pagination, 'user' => $user]);
    }

    /**
     * @Route ("/posts/{slug}", name="app_post")
     * @param $slug
     * @return Response
     */
    public function showPost($slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->findOneBySlug($slug);
        $entityManager->getRepository(Post::class)->incrementViewCount($post);
        $entityManager->flush();
        $tags = $post->getTags();
        return $this->render('post.html.twig', ['post' => $post, 'tags' => $tags]);
    }

    /**
     * @Route ("/create-post", name="app_create_post")
     */
    public function addPost(TagRepository $tagRepository)
    {
        $data = [
            'title' => '',
            'text' => '',
            'datePublished' => '',
            'counter' => '',
            'slug' => '',
        ];
        $this->postService->createPost($data);

        return new Response("Created object");
    }

    /**
     * @Route ("/disable-post/{id}", name="app_disable_post")
     * @param int $id
     * @return RedirectResponse
     */
    public function disablePost(int $id){
        $this->postService->disablePost($id);
        return new RedirectResponse("/");
    }

    /**
     * @Route ("/enable-post/{id}", name="app_enable_post")
     * @param int $id
     * @return RedirectResponse
     */
    public function enablePost(int $id){
        $this->postService->enablePost($id);
        return new RedirectResponse("/");
    }

    /**
     * @Route ("/delete-post/{id}", name="app_delete_post")
     * @param int $id
     * @return RedirectResponse
     */
    public function deletePost(int $id){
        $this->postService->deletePost($id);
        return new RedirectResponse("/");
    }

}
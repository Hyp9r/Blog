<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Service\PostService;
use ContainerRTFgIzf\getKnpPaginatorService;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\DateTime;

class PostsController extends AbstractController
{
    /**
     * @var PostService
     */
    protected $postService;
    private $security;

    /**
     * PostsController constructor.
     * @param PostService $postService
     * @param Security $security
     */
    public function __construct(PostService $postService,Security $security)
    {
        $this->postService = $postService;
        $this->security = $security;
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

        //Depending on user role, fetch only visible posts, or all if user is admin
        $data = null;

        if($this->security->isGranted('ROLE_ADMIN')){
            $data = $repository->findAllFromLatestDate();
        }else{
            $data = $repository->findAllVisible();
        }

        /**
         * @var $paginator Paginator
         */
        $pagination = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 2)
        );


        return $this->render('homepage.html.twig', ['posts' => $pagination]);
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
     */
    public function updatePost(Request $request, int $id){
        $post = $this->postService->getPost($id);

        $form = $this->createForm(PostType::class, $post);

        $form->get('title')->setData($post->getTitle());
        $form->get('text')->setData($post->getText());
        $form->get('tags')->setData($post->getTags());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

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

}
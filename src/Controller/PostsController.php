<?php


namespace App\Controller;


use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(){
        //Get all posts from DB
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        if(!$posts){
            throw $this->createNotFoundException("No posts found");
        };

        return $this->render('homepage.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route ("/posts/{slug}", name="app_post")
     */
    public function showPost($slug){
        $entityManager = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);
        $post->updateViewCount();
        $entityManager->flush();
        return $this->render('post.html.twig', ['post' => $post]);
    }

}
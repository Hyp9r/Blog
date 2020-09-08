<?php


namespace App\Controller;


use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function homepage(){
        //Get all posts from DB
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        //$posts = $this->getDoctrine()->getRepository(Post::class)->find(1);
        if(!$posts){
            throw $this->createNotFoundException("No posts found");
        };

        return $this->render('homepage.html.twig', ['posts' => $posts]);
            //'id' => $id, 'title' => $title, 'text' => $text, 'tags' => $tags, 'date' => $date]);
    }

    /**
     * @Route ("/posts/{slug}")
     */
    public function showPost($slug){
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);
        return $this->render('post.html.twig', ['post' => $post]);
        //'id' => $id, 'title' => $title, 'text' => $text, 'tags' => $tags, 'date' => $date]);
    }

}
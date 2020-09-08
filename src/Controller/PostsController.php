<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function homepage(){
        $id = 1;
        $title = "Hello world";
        $text = "This is my first post";
        $tags = "html";
        $date = date("Y-m-d h:i:sa");
        return $this->render('homepage.html.twig', ['id' => $id, 'title' => $title, 'text' => $text, 'tags' => $tags, 'date' => $date]);
    }

    /**
     * @Route ("/posts/{slug}")
     */
    public function showPost(){
        return new Response("Post");
    }

}
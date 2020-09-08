<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController
{

    /**
     * @Route("/")
     */
    public function homepage(){
        return new Response("hello world");
    }

    /**
     * @Route ("/posts/{slug}")
     */
    public function showPost(){
        return new Response("Post");
    }

}
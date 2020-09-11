<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/api/post", name="api_post")
 */
class PostsController extends AbstractController
{
    /**
     * @Route ("s", name="s")
     */
    public function getAllPosts()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        if (!$posts) {
            throw $this->createNotFoundException("No posts found");
        }

        $arrayCollection = array();

        foreach ($posts as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'views' => $item->getCounter(),
                'date_published' => $item->getDatePublished(),
                'slug' => $item->getSlug()
            );
        }

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route ("/test/{slug}", name="post_by_slug_test")
     * @ParamConverter("post", class="App\Entity\Post")
     */
    public function getBySlugTest(Post $post)
    {
        dd($post);
    }

    /**
     * @Route ("/{slug}", name="post_by_slug")
     * @param string $slug
     * @return JsonResponse
     */
    public function getPost(string $slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->findOneBySlug($slug);
        if (!$post) {
            throw  $this->createNotFoundException("Post not found 1234#%689"); // post-not-found-1234689
        }

        $entityManager->getRepository(Post::class)->incrementViewCount($post);

        $response = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'text' => $post->getText(),
            'date_published' => $post->getDatePublished(),
            'slug' => $post->getSlug(),
            'counter' => $post->getCounter()
        ];
        return new JsonResponse($response);
    }
}
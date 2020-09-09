<?php


namespace App\Controller\Api;


use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{

    /**
     * @Route ("/api/posts", name="api_posts")
     */
    public function getAllPostsJSON(){
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        if(!$posts){
            throw $this->createNotFoundException("No posts found");
        };

        $arrayCollection = array();

        foreach($posts as $item) {
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
     * @Route ("/api/post/{slug}", name="api_post_by_slug")
     */
    public function getPost($slug){
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);
        if(!$post){
            throw  $this->createNotFoundException("Post not found");
        }

        $this->updateViewCount($post->getId());

        $response = array('id' => $post->getId(), 'title' => $post->getTitle(), 'text' => $post->getText(), 'date_published' => $post->getDatePublished(), 'slug' => $post->getSlug(), 'counter' => $post->getCounter());
        return new JsonResponse($response);
    }

    private function updateViewCount($id){
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);

        if(!$post){
            throw $this->createNotFoundException("Post not found");
        }

        $currentAmountOfViews = $post->getCounter();
        $post->setCounter($currentAmountOfViews + 1);

        $entityManager->flush();
    }

}
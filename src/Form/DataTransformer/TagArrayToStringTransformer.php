<?php


namespace App\Form\DataTransformer;


use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

use function Symfony\Component\String\u;

class TagArrayToStringTransformer implements DataTransformerInterface
{
    private $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    public function transform($tags)
    {
        return implode(',', $tags);
    }

    public function reverseTransform($string): array
    {
        if(null === $string || u($string)->isEmpty()){
            return [];
        }

        $titles = array_filter(array_unique(array_map('trim', u($string)->split(','))));

        $tags = $this->tags->findBy(['title' => $titles]);

        $newTitles = array_diff($titles, $tags);
        foreach ($newTitles as $title){
            $tag = new Tag();
            $tag->setTitle($title);
            $tags[] = $tag;
        }

        return $tags;
    }


}
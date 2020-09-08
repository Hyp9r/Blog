<?php

namespace App\Entity;

use App\Repository\PostTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostTagRepository::class)
 */
class PostTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class)
     */
    private $post_id;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     */
    private $tag_id;

    public function __construct()
    {
        $this->post_id = new ArrayCollection();
        $this->tag_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPostId(): Collection
    {
        return $this->post_id;
    }

    public function addPostId(Post $postId): self
    {
        if (!$this->post_id->contains($postId)) {
            $this->post_id[] = $postId;
        }

        return $this;
    }

    public function removePostId(Post $postId): self
    {
        if ($this->post_id->contains($postId)) {
            $this->post_id->removeElement($postId);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTagId(): Collection
    {
        return $this->tag_id;
    }

    public function addTagId(Tag $tagId): self
    {
        if (!$this->tag_id->contains($tagId)) {
            $this->tag_id[] = $tagId;
        }

        return $this;
    }

    public function removeTagId(Tag $tagId): self
    {
        if ($this->tag_id->contains($tagId)) {
            $this->tag_id->removeElement($tagId);
        }

        return $this;
    }
}

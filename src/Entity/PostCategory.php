<?php

namespace App\Entity;

use App\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostCategoryRepository::class)]
class PostCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'postCategory', targetEntity: Post::class)]
    private Collection $title;

    public function __construct()
    {
        $this->title = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getTitle(): Collection
    {
        return $this->title;
    }

    public function addTitle(Post $title): self
    {
        if (!$this->title->contains($title)) {
            $this->title->add($title);
            $title->setPostCategory($this);
        }

        return $this;
    }

    public function removeTitle(Post $title): self
    {
        if ($this->title->removeElement($title)) {
            // set the owning side to null (unless already changed)
            if ($title->getPostCategory() === $this) {
                $title->setPostCategory(null);
            }
        }

        return $this;
    }
}

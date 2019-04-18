<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogCategoryRepository")
 */
class BlogCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $languageKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionLanguageKey;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="category")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguageKey(): ?string
    {
        return $this->languageKey;
    }

    public function setLanguageKey(string $languageKey): self
    {
        $this->languageKey = $languageKey;

        return $this;
    }

    public function getUrlName(): ?string
    {
        return $this->urlName;
    }

    public function setUrlName(string $urlName): self
    {
        $this->urlName = $urlName;

        return $this;
    }

    public function getDescriptionLanguageKey(): ?string
    {
        return $this->descriptionLanguageKey;
    }

    public function setDescriptionLanguageKey(?string $descriptionLanguageKey): self
    {
        $this->descriptionLanguageKey = $descriptionLanguageKey;

        return $this;
    }

    public function __toString()
    {
        return $this->getLanguageKey();
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(BlogPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(BlogPost $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}

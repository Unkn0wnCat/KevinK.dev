<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoAlbumRepository")
 */
class PhotoAlbum
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array")
     */
    private $title;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Project", inversedBy="linkedPhotoAlbums")
     */
    private $linkedProjects;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photo", inversedBy="photoAlbums")
     */
    private $photos;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $contentCards;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BlogPost", inversedBy="linkedPhotoAlbums")
     */
    private $linkedBlogPosts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PhotoGallery", mappedBy="albums")
     */
    private $photoGalleries;

    public function __construct()
    {
        $this->linkedProjects = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->linkedBlogPosts = new ArrayCollection();
        $this->photoGalleries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getLinkedProjects(): Collection
    {
        return $this->linkedProjects;
    }

    public function addLinkedProject(Project $linkedProject): self
    {
        if (!$this->linkedProjects->contains($linkedProject)) {
            $this->linkedProjects[] = $linkedProject;
        }

        return $this;
    }

    public function removeLinkedProject(Project $linkedProject): self
    {
        if ($this->linkedProjects->contains($linkedProject)) {
            $this->linkedProjects->removeElement($linkedProject);
        }

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
        }

        return $this;
    }

    public function getContentCards()
    {
        return $this->contentCards;
    }

    public function setContentCards($contentCards): self
    {
        $this->contentCards = $contentCards;

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getLinkedBlogPosts(): Collection
    {
        return $this->linkedBlogPosts;
    }

    public function addLinkedBlogPost(BlogPost $linkedBlogPost): self
    {
        if (!$this->linkedBlogPosts->contains($linkedBlogPost)) {
            $this->linkedBlogPosts[] = $linkedBlogPost;
        }

        return $this;
    }

    public function removeLinkedBlogPost(BlogPost $linkedBlogPost): self
    {
        if ($this->linkedBlogPosts->contains($linkedBlogPost)) {
            $this->linkedBlogPosts->removeElement($linkedBlogPost);
        }

        return $this;
    }

    /**
     * @return Collection|PhotoGallery[]
     */
    public function getPhotoGalleries(): Collection
    {
        return $this->photoGalleries;
    }

    public function addPhotoGallery(PhotoGallery $photoGallery): self
    {
        if (!$this->photoGalleries->contains($photoGallery)) {
            $this->photoGalleries[] = $photoGallery;
            $photoGallery->addAlbum($this);
        }

        return $this;
    }

    public function removePhotoGallery(PhotoGallery $photoGallery): self
    {
        if ($this->photoGalleries->contains($photoGallery)) {
            $this->photoGalleries->removeElement($photoGallery);
            $photoGallery->removeAlbum($this);
        }

        return $this;
    }
}

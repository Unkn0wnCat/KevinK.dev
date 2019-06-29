<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoGalleryRepository")
 */
class PhotoGallery
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
     * @ORM\ManyToMany(targetEntity="App\Entity\PhotoAlbum", inversedBy="photoGalleries")
     */
    private $albums;

    /**
     * @ORM\Column(type="boolean")
     */
    private $listed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Photo")
     */
    private $CoverImage;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
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
     * @return Collection|PhotoAlbum[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(PhotoAlbum $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
        }

        return $this;
    }

    public function removeAlbum(PhotoAlbum $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
        }

        return $this;
    }

    public function getListed(): ?bool
    {
        return $this->listed;
    }

    public function setListed(bool $listed): self
    {
        $this->listed = $listed;

        return $this;
    }

    public function getCoverImage(): ?Photo
    {
        return $this->CoverImage;
    }

    public function setCoverImage(?Photo $CoverImage): self
    {
        $this->CoverImage = $CoverImage;

        return $this;
    }
}

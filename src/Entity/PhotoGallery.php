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
     * @ORM\Column(type="boolean")
     */
    private $directlyShowPhotos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photo", inversedBy="photoGalleries")
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PhotoAlbum", inversedBy="photoGalleries")
     */
    private $albums;

    /**
     * @ORM\Column(type="boolean")
     */
    private $listed;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
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

    public function getDirectlyShowPhotos(): ?bool
    {
        return $this->directlyShowPhotos;
    }

    public function setDirectlyShowPhotos(bool $directlyShowPhotos): self
    {
        $this->directlyShowPhotos = $directlyShowPhotos;

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
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $src;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $gps_location;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $captureTimestamp;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PhotoAlbum", mappedBy="photos")
     */
    private $photoAlbums;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PhotoGallery", mappedBy="photos")
     */
    private $photoGalleries;

    /**
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * Photo constructor.
     * @param string $src
     * @param \DateTime $timeStamp
     * @param int $width
     * @param int $height
     * @param string $title
     * @param string $description
     */
    public function __construct(string $src, \DateTime $timeStamp, int $width, int $height, $title = "", $description = "")
    {
        $this->photoAlbums = new ArrayCollection();
        $this->photoGalleries = new ArrayCollection();
        $this->src = $src;
        $this->captureTimestamp = $timeStamp;
        $this->width = $width;
        $this->height = $height;

        if($title != "") $this->title = $title;
        if($description != "") $this->description = $description;
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

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getGpsLocation()
    {
        return $this->gps_location;
    }

    public function setGpsLocation($gps_location): self
    {
        $this->gps_location = $gps_location;

        return $this;
    }

    public function getCaptureTimestamp(): ?\DateTimeInterface
    {
        return $this->captureTimestamp;
    }

    public function setCaptureTimestamp(?\DateTimeInterface $captureTimestamp): self
    {
        $this->captureTimestamp = $captureTimestamp;

        return $this;
    }

    /**
     * @return Collection|PhotoAlbum[]
     */
    public function getPhotoAlbums(): Collection
    {
        return $this->photoAlbums;
    }

    public function addPhotoAlbum(PhotoAlbum $photoAlbum): self
    {
        if (!$this->photoAlbums->contains($photoAlbum)) {
            $this->photoAlbums[] = $photoAlbum;
            $photoAlbum->addPhoto($this);
        }

        return $this;
    }

    public function removePhotoAlbum(PhotoAlbum $photoAlbum): self
    {
        if ($this->photoAlbums->contains($photoAlbum)) {
            $this->photoAlbums->removeElement($photoAlbum);
            $photoAlbum->removePhoto($this);
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
            $photoGallery->addPhoto($this);
        }

        return $this;
    }

    public function removePhotoGallery(PhotoGallery $photoGallery): self
    {
        if ($this->photoGalleries->contains($photoGallery)) {
            $this->photoGalleries->removeElement($photoGallery);
            $photoGallery->removePhoto($this);
        }

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }
}

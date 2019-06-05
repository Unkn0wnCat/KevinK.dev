<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
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
    private $urlName;

    /**
     * @ORM\Column(type="json_array")
     */
    private $name;

    /**
     * @ORM\Column(type="json_array")
     */
    private $headline;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $externalURL;

    /**
     * @ORM\Column(type="boolean")
     */
    private $redirectToExternal;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $about;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $modules;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $indicatorTranslationString;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $indicatorClass;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHeadline()
    {
        return $this->headline;
    }

    public function setHeadline($headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function getImage(): ?string
    {
        if((substr($this->image, 0, 1) === "/")) {
            return "https://kevink.dev".$this->image;
        } else return $this->image;
    }

    public function getImageRaw(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getExternalURL(): ?string
    {
        return $this->externalURL;
    }

    public function setExternalURL(?string $externalURL): self
    {
        $this->externalURL = $externalURL;

        return $this;
    }

    public function getRedirectToExternal(): ?bool
    {
        return $this->redirectToExternal;
    }

    public function setRedirectToExternal(bool $redirectToExternal): self
    {
        $this->redirectToExternal = $redirectToExternal;

        return $this;
    }

    public function getAbout()
    {
        return $this->about;
    }

    public function setAbout($about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function setModules($modules): self
    {
        $this->modules = $modules;

        return $this;
    }

    public function getIndicatorTranslationString(): ?string
    {
        return $this->indicatorTranslationString;
    }

    public function setIndicatorTranslationString(string $indicatorTranslationString): self
    {
        $this->indicatorTranslationString = $indicatorTranslationString;

        return $this;
    }

    public function getIndicatorClass(): ?string
    {
        return $this->indicatorClass;
    }

    public function setIndicatorClass(string $indicatorClass): self
    {
        $this->indicatorClass = $indicatorClass;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}

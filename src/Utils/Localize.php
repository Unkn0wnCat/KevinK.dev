<?php
namespace App\Utils;
use App\Entity\BlogPost;

class Localize {
    /**
     * @param BlogPost $post
     * @param string $locale
     * @return BlogPost
     */
    public function getLocalizedContent(BlogPost $post, string $locale) : BlogPost {
        return $post
            ->setTitle($this->getLocalizedString($post->getTitle(), $locale))
            ->setSummary($this->getLocalizedString($post->getSummary(), $locale))
            ->setContent($this->getLocalizedString($post->getContent(), $locale))
            ;
    }

    /**
     * @param array $content
     * @param string $locale
     * @param string|boolean $fallback
     * @return string
     */
    public function getLocalizedString(array $content, string $locale, string $fallback = "Untitled") : string
    {
        return array_key_exists(
            $locale,
            $content) ?
            $content[$locale] : (
            array_key_exists(
                $this->getParameter('locale'),
                $content) ?
                $content[$this->getParameter('locale')] :
                $fallback
            );
    }
}
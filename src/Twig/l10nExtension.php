<?php


namespace App\Twig;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Utils\Localize;

class l10nExtension extends AbstractExtension
{

    private $localize;
    private $requestStack;

    public function __construct(RequestStack $requestStack, Localize $localize)
    {
        $this->localize = $localize;
        $this->requestStack = $requestStack;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('decodeLocaleArray', [$this, 'decodeLocaleArray'])
        ];
    }

    /**
     * Decode Locale Array to a supported locale
     * @param array $array
     * @param string|bool $fallback
     * @return string
     */
    public function decodeLocaleArray(array $array, $fallback = false) {
        /**
         * @var Request
         */
        $request = $this->requestStack->getCurrentRequest();
        return $this->localize->getLocalizedString($array, $request->getLocale(), $fallback);
    }
}
<?php
namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $this->getBrowserLanguage([
            "en", "de"
        ], $defaultLocale);
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    /**
     * @param array $available
     * @param string $default
     * @return bool|string
     */
    private function getBrowserLanguage($available = [], $default = 'en' ) {
        if ( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) ) {
            $languages = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
            if ( empty( $available ) ) {
                return $languages[ 0 ];
            }
            foreach ( $languages as $lang ){
                $lang = substr( $lang, 0, 2 );
                if( in_array( $lang, $available ) ) {
                    return $lang;
                }
                if( in_array( explode("-", $lang)[0], $available ) ) {
                    return explode("-", $lang)[0];
                }
            }
        }
        return $default;
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            //KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
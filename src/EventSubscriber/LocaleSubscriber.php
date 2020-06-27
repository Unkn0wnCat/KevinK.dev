<?php
namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

    }

    public function onKernelResponse(FilterResponseEvent $event) {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if ($locale = $request->attributes->get('_locale')) {
            $response->headers->setCookie(new Cookie("lang", $locale, strtotime('now + 1 month'), "/", null, false, true, false, "Lax"));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
            KernelEvents::RESPONSE => [['onKernelResponse', 20]]
        ];
    }
}
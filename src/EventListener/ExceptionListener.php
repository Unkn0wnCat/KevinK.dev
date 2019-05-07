<?php


namespace App\EventListener;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    private $locales = array("en", "de");

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        if($exception instanceof NotFoundHttpException) {
            if($event->getRequest()->getRequestUri() && !$this->urlHasLocale($event->getRequest()->getRequestUri())) {
                $event->setResponse(new RedirectResponse("/".$this->locales[0]."".$event->getRequest()->getRequestUri()));
            }
        }


        //
    }

    private function urlHasLocale(string $url) {

        foreach ($this->locales as $locale) {
            if($this->startsWith($url, "/".$locale."/")) {
                return true;
            }
        }

        return false;
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}
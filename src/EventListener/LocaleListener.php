<?php
// src/EventListener/LocaleListener.php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class LocaleListener implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $session = $event->getRequest()->getSession()->get('user_locale_language');

        $httpLanguageAccept = $event->getRequest()->getSession()->get('http-language-accept');
        $languageTags = explode(',', $httpLanguageAccept);
        $firstLanguageTag = trim($languageTags[0]);
        $languageCode = explode('-', $firstLanguageTag)[0];

        if(!is_null($session)){
            $event->getRequest()->setLocale($session);
        }
        else if($languageCode == 'fr' or $languageCode == 'en'){
            $event->getRequest()->setLocale($languageCode);
        }else{
            $event->getRequest()->setLocale('en');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 20],
        ];
    }
}
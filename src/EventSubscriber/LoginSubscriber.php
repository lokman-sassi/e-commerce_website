<?php

namespace App\EventSubscriber;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

Class LoginSubscriber implements EventSubscriberInterface
{

    private $em;
    private $security;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function onLogin()
    {
        $user = $this->security->getUser();
        $user->setLastLoginAt(new DateTime());
        $this->em->flush();

    }
    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLogin',
        ];
    }
}
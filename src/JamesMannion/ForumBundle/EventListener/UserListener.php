<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 10/08/14
 * Time: 20:25
 */

namespace JamesMannion\ForumBundle\EventListener;

use JamesMannion\ForumBundle\Constants\AppConfig;
use JamesMannion\ForumBundle\Event\UserEvent;
use Swift_Mailer;
use Swift_Message;
use JamesMannion\ForumBundle\Event\PostEvent;
use JamesMannion\ForumBundle\Entity\Thread;
use JamesMannion\ForumBundle\Entity\Post;
use JamesMannion\ForumBundle\Entity\User;

class UserListener
{
    protected $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onUserRegisteredEvent(UserEvent $event)
    {
        /** @var User $user */
        $newlyRegisteredUser = $event->getUser();

        $message = Swift_Message::newInstance()
            ->setSubject('Welcome to ' . AppConfig::SYSTEM_NAME . ', here are your Activation Instructions')
            ->setFrom(AppConfig::ADMIN_EMAIL)
            ->setTo($newlyRegisteredUser->getEmail())
            ->setBody("Welcome to " . AppConfig::SYSTEM_NAME . ' here is your activation link')
        ;
        $this->mailer->send($message);
    }

} 
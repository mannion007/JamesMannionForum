<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 10/08/14
 * Time: 20:25
 */

namespace JamesMannion\ForumBundle\EventListener;

use JamesMannion\ForumBundle\Constants\AppConfig;
use Swift_Mailer;
use Swift_Message;
use JamesMannion\ForumBundle\Event\PostEvent;
use JamesMannion\ForumBundle\Entity\Thread;
use JamesMannion\ForumBundle\Entity\Post;
use JamesMannion\ForumBundle\Entity\User;

class PostListener
{
    protected $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onPostCreatedEvent(PostEvent $event)
    {
       /** @var Post $post */
        $post = $event->getPost();

        /** @var Thread $thread */
        $thread = $post->getThread();

        /** @var User[] $usersWatchingThread */
        $usersWatchingThread = $thread->getWatchers();

        if(0 < count($usersWatchingThread)) {
            foreach($usersWatchingThread as $userWatchingThread) {
                $message = Swift_Message::newInstance()
                    ->setSubject('New post created on ' . $thread->getTitle())
                    ->setFrom(AppConfig::ADMIN_EMAIL)
                    ->setTo($userWatchingThread->getEmail())
                    ->setBody("Hey, somebody left a new comment on a thread you're subscribed to! It says: " . $post->getBody())
                ;
                //$this->mailer->send($message);
            }
        }
    }

} 
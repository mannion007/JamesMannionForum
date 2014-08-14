<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 10/08/14
 * Time: 22:04
 */

namespace JamesMannion\ForumBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use JamesMannion\ForumBundle\Entity\User;

class UserEvent extends Event
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}

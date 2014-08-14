<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 10/08/14
 * Time: 20:20
 */

namespace JamesMannion\ForumBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use JamesMannion\ForumBundle\Entity\Post;

class PostEvent extends Event
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @param \JamesMannion\ForumBundle\Entity\Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return \JamesMannion\ForumBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }



} 
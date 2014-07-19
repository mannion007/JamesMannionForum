<?php

namespace JamesMannion\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="posts")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id")
     */
    private $thread;

    /**
     * @var boolean
     *
     * @ORM\Column(name="primaryPost", type="boolean")
     */
    private $primaryPost = false;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Post
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set author
     *
     * @param \JamesMannion\ForumBundle\Entity\User $author
     * @return Post
     */
    public function setAuthor(\JamesMannion\ForumBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \JamesMannion\ForumBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set thread
     *
     * @param \JamesMannion\ForumBundle\Entity\Thread $thread
     * @return Post
     */
    public function setThread(\JamesMannion\ForumBundle\Entity\Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \JamesMannion\ForumBundle\Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }
    /**
     * @param boolean $primaryPost
     */
    public function setPrimaryPost($primaryPost)
    {
        $this->primaryPost = $primaryPost;
    }

    /**
     * @return boolean
     */
    public function getPrimaryPost()
    {
        return $this->primaryPost;
    }

}

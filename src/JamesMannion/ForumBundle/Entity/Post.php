<?php

namespace JamesMannion\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\ManyToOne(targetEntity="Thread", cascade={"persist"}))
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", nullable=false)
     */
    private $thread;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="postLikes", cascade={"persist"})
     * @ORM\JoinTable(name="PostLikes")
     **/
    private $likers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="primaryPost", type="boolean")
     */
    private $primaryPost = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    public function __construct()
    {
        $this->likers = new ArrayCollection();
    }

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
    public function setAuthor(User $author = null)
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
    public function setThread(Thread $thread = null)
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
     * @param User $userToAdd
     * @return $this
     */
    public function addLiker(User $userToAdd)
    {
        $this->likers[] = $userToAdd;
        return $this;
    }

    /**
     * @param User $userToRemove
     */
    public function removeLiker(User $userToRemove)
    {
        $this->likers->removeElement($userToRemove);
    }

    /**
     * @return ArrayCollection
     */
    public function getLikers()
    {
        return $this->likers;
    }

    /**
     * @param User $userToCheck
     * @return bool
     */
    public function hasLiker(User $userToCheck)
    {
        if (true === $this->getLikers()->contains($userToCheck)) {
            return true;
        }
        return false;
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

    /**
     * @ORM\PrePersist
     * @return $this
     */
    public function setCreated()
    {
        $this->created = new \DateTime();

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @ORM\PreUpdate
     * @return $this
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateThread()
    {
        $this->thread->setUpdated();
    }

    /**
     * @param User $userToCheck
     * @return bool
     */
    public function isAuthor(User $userToCheck)
    {
        if($userToCheck->getId() == $this->getAuthor()->getId())
        {
            return true;
        }
        return false;
    }
}

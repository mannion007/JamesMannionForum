<?php

namespace JamesMannion\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Thread
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Thread
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
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="threads")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="thread")
     */
    protected $posts;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="author", type="object")
     */
    private $author;


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
     * Set title
     *
     * @param string $title
     * @return Thread
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Thread
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return $this
     * @ORM\PrePersist
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime('now');

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set author
     *
     * @param \stdClass $author
     * @return Thread
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \stdClass 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set room
     *
     * @param \JamesMannion\ForumBundle\Entity\Room $room
     * @return Thread
     */
    public function setRoom(\JamesMannion\ForumBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \JamesMannion\ForumBundle\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add posts
     *
     * @param \JamesMannion\ForumBundle\Entity\Post $posts
     * @return Thread
     */
    public function addPost(\JamesMannion\ForumBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \JamesMannion\ForumBundle\Entity\Post $posts
     */
    public function removePost(\JamesMannion\ForumBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}

<?php

namespace JamesMannion\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Thread
 *
 * @ORM\Table(name="Thread")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Thread
{
    /**
     * @var integer
     *
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
     * @ORM\OneToMany(targetEntity="Post", mappedBy="thread", cascade={"persist"}))
     */
    protected $posts;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="threadWatches", cascade={"persist"})
     * @ORM\JoinTable(name="ThreadWatches")
     **/
    protected $watchers;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     */
    protected $views = 0;

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
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="threads")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->watchers = new ArrayCollection();
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
     * @ORM\PrePersist
     * @return $this
     */
    public function setCreated()
    {
        $this->created = new \DateTime();

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
    public function setRoom(Room $room = null)
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
     * Add posts
     *
     * @param \JamesMannion\ForumBundle\Entity\Post $posts
     * @return Thread
     */
    public function addPost(Post $posts)
    {
        $this->posts[] = $posts;

        $posts->setThread($this);

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \JamesMannion\ForumBundle\Entity\Post $posts
     */
    public function removePost(Post $posts)
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

    /**
     * @param User $userToAdd
     * @return $this
     */
    public function addWatcher(User $userToAdd)
    {
        $this->watchers[] = $userToAdd;
        return $this;
    }

    /**
     * @param User $userToRemove
     */
    public function removeWatcher(User $userToRemove)
    {
        $this->watchers->removeElement($userToRemove);
    }

    /**
     * @return ArrayCollection
     */
    public function getWatchers()
    {
        return $this->watchers;
    }

    /**
     * @param User $userToCheck
     * @return bool
     */
    public function hasWatcher(User $userToCheck)
    {
        if (true === $this->getWatchers()->contains($userToCheck)) {
            return true;
        }
        return false;
    }

    /**
     * @param int $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @return $this
     */
    public function addView()
    {
        $this->views ++;
        return $this;
    }
}

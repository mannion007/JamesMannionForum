<?php

namespace JamesMannion\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JamesMannion\ForumBundle\Constants\Validation;
use Bafford\PasswordStrengthBundle\Validator\Constraints\PasswordStrength;
use JamesMannion\ForumBundle\Constants\AppConfig;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @ORM\OneToMany(targetEntity="Post", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="Thread", mappedBy="author")
     */
    private $threads;

    /**
     * @ORM\ManyToMany(targetEntity="Thread", mappedBy="watchers")
     *
     **/
    private $threadWatches;

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="likers")
     *
     **/
    private $postLikes;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, unique=true, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="MemorableQuestion", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="memorable_question_id", referencedColumnName="id", nullable=false)
     */
    private $memorableQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="memorable_answer", type="string", length=255, nullable=false)
     */
    private $memorableAnswer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = false;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Login", mappedBy="user", cascade={"persist"})
     * @OrderBy({"created" = "ASC"})
     */
    private $logins;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->threads = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Post $posts
     * @return $this
     */
    public function addPost(Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * @param Post $posts
     */
    public function removePost(Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $threads
     */
    public function setThreads($threads)
    {
        $this->threads = $threads;
    }

    /**
     * @return mixed
     */
    public function getThreads()
    {
        return $this->threads;
    }

    /**
     * @param mixed $threadsWatching
     */
    public function setThreadWatches($threadsWatching)
    {
        $this->threadWatches = $threadsWatching;
    }

    /**
     * @return mixed
     */
    public function getThreadWatches()
    {
        return $this->threadWatches;
    }

    /**
     * @param mixed $likes
     */
    public function setPostLikes($likes)
    {
        $this->postLikes = $likes;
    }

    /**
     * @return mixed
     */
    public function getPostLikes()
    {
        return $this->postLikes;
    }

    /**
     * @param Post $postToCheck
     * @return bool
     */
    public function hasLike(Post $postToCheck)
    {
        if (true === $this->postLikes->hasElement($postToCheck)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreated()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $memorableQuestion
     */
    public function setMemorableQuestion($memorableQuestion)
    {
        $this->memorableQuestion = $memorableQuestion;
    }

    /**
     * @return mixed
     */
    public function getMemorableQuestion()
    {
        return $this->memorableQuestion;
    }

    /**
     * @param string $memorableAnswer
     */
    public function setMemorableAnswer($memorableAnswer)
    {
        $this->memorableAnswer = $memorableAnswer;
    }

    /**
     * @return string
     */
    public function getMemorableAnswer()
    {
        return $this->memorableAnswer;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param Login $login
     */
    public function addLogin(Login $login)
    {
        $this->logins[] = $login;
    }

    /**
     * @param Login $login
     */
    public function removeLogin(Login $login)
    {
        $this->logins->removeElement($login);
    }

    /**
     * @param mixed $logins
     */
    public function setLogins($logins)
    {
        $this->logins = $logins;
    }

    /**
     * @return mixed
     */
    public function getLogins()
    {
        return $this->logins;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(
            new UniqueEntity(
                array(
                    'fields'        => 'email',
                    'message'       => Validation::REGISTRATION_EMAIL_UNIQUE,
                    'groups'        => array('User')
                )
            )
        );

        $metadata->addConstraint(
            new UniqueEntity(
                array(
                    'fields'        => 'username',
                    'message'       => Validation::REGISTRATION_USERNAME_UNIQUE,
                    'groups'        => array('User')
                )
            )
        );

        $metadata->addPropertyConstraint(
            'password',
            new Assert\Length(
                array(
                    'min'           => AppConfig::PASSWORD_MIN_LENGTH,
                    'max'           => AppConfig::PASSWORD_MAX_LENGTH,
                    'minMessage'    => Validation::REGISTRATION_PASSWORD_MIN_LENGTH,
                    'maxMessage'    => Validation::REGISTRATION_PASSWORD_MAX_LENGTH,
                    'groups'        => array('User')
                )
            )
        );

        $metadata->addPropertyConstraint(
            'password',
            new PasswordStrength(
                array(
                    'minLength'              => AppConfig::PASSWORD_MIN_LENGTH,
                    'requireLetters'         => AppConfig::PASSWORD_REQUIRES_LETTERS,
                    'requireCaseDiff'        => AppConfig::PASSWORD_REQUIRES_CASE_DIFFERENCE,
                    'requireNumbers'         => AppConfig::PASSWORD_REQUIRES_NUMBERS,
                    'missingLettersMessage'  => Validation::REGISTRATION_PASSWORD_REQUIRES_LETTERS,
                    'missingNumbersMessage'  => Validation::REGISTRATION_PASSWORD_REQUIRES_NUMBERS,
                    'requireCaseDiffMessage' => Validation::REGISTRATION_PASSWORD_REQUIRES_CASE_DIFFERENCE
                )
            )
        );

        $metadata->setGroupSequence(array('User', 'Strict'));

    }

}

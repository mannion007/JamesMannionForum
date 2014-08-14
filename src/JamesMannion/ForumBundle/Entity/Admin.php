<?php

namespace JamesMannion\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Bafford\PasswordStrengthBundle\Validator\Constraints as BAssert;


/**
 * User
 *
 * @ORM\Table(name="AdminUser")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Admin implements AdvancedUserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, unique=true, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=45, unique=true, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, unique=true, nullable=false)
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
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set created
     *
     * @ORM\PrePersist
     * @return User
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
     * Set updated
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return User
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();

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
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
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
        return array('ROLE_ADMIN');
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

    /**
     * @param Mapping\ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(Mapping\ClassMetadata $metadata)
    {
        $metadata->addConstraint(
            new UniqueEntity(
                array(
                    'fields'        => 'email',
                    'message'       => Validator::EMAIL_UNIQUE,
                    'groups'        => array('Admin')
                )
            )
        );

        $metadata->addConstraint(
            new UniqueEntity(
                array(
                    'fields'        => 'username',
                    'message'       => Validator::USERNAME_UNIQUE,
                    'groups'        => array('Admin')
                )
            )
        );

        $metadata->addPropertyConstraint(
            'password',
            new Assert\Length(
                array(
                    'min'           => AppConfig::PASSWORD_MIN_LENGTH,
                    'max'           => AppConfig::PASSWORD_MAX_LENGTH,
                    'minMessage'    => Validator::PASSWORD_MIN_LENGTH,
                    'maxMessage'    => Validator::PASSWORD_MAX_LENGTH,
                    'groups'        => array('Admin')
                )
            )
        );

        $metadata->addPropertyConstraint(
            'password',
            new Assert\Length(
                array(
                    'min'           => AppConfig::PASSWORD_MIN_LENGTH,
                    'max'           => AppConfig::PASSWORD_MAX_LENGTH,
                    'minMessage'    => Validator::PASSWORD_MIN_LENGTH,
                    'maxMessage'    => Validator::PASSWORD_MAX_LENGTH,
                    'groups'        => array('Admin')
                )
            )
        );

        $metadata->addPropertyConstraint(
            'password',
           new BAssert\PasswordStrength(
               array(
               'minLength'              => AppConfig::PASSWORD_MIN_LENGTH,
               'requireLetters'         => AppConfig::PASSWORD_REQUIRES_LETTERS,
               'requireCaseDiff'        => AppConfig::PASSWORD_REQUIRES_CASE_DIFFERENCE,
               'requireNumbers'         => AppConfig::PASSWORD_REQUIRES_NUMBERS,
               'missingLettersMessage'  => Validator::PASSWORD_LETTERS,
               'missingNumbersMessage'  => Validator::PASSWORD_NUMBERS,
               'requireCaseDiffMessage' => Validator::PASSWORD_CASE_DIFFERENCE
               )
           )
        );

        $metadata->setGroupSequence(array('Admin', 'Strict'));

    }
}

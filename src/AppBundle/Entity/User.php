<?php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity("email")
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Groups({"user"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column
     * @JMS\Expose
     * @JMS\Groups({"user", "show"})
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column
     */
    private $password;

    /**
     * @var string
     * @ORM\Column
     * @Assert\Email
     * @JMS\Expose
     * @JMS\Groups({"user"})
     */
    private $email;

    /**
     * @var string[]
     * @ORM\Column(type="json_array")
     */
    private $roles;

    private $salt;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Show", mappedBy="author")
     */
    private $shows;

    public function __construct()
    {
        $this->shows = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    //No Id setter

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
        //
    }

    /**
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function setUsername(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function addShow(Show $show)
    {
        if (!$this->shows->contains($show)) {
            $this->shows->add($show);
        }
    }

    public function removeShow(Show $show)
    {
        // TODO: unlink show when user deleted ?
        $this->shows->remove($show);
    }

    public function getShows()
    {
        return $this->shows;
    }
}

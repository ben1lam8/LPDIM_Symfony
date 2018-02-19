<?php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column
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
     */
    private $email;

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
        return ['ROLE_USER', 'ROLE_ADMIN'];
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
        if(!$this->shows->contains($show))
            $this->shows->add($show);
    }

    public function removeShow(Show $show)
    {
        //TODO: unlink show when show deleted
        $this->shows->remove($show);
    }
}

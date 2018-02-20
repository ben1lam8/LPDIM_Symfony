<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Category
 * @package AppBundle\Entity
 * @ORM\Entity
 * @UniqueEntity("name", message="{{value}} is already in database")
 * @JMS\ExclusionPolicy("all")
 */
class Category
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Groups({"category"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     * @JMS\Expose
     * @JMS\Groups({"category", "show"})
     */
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    // No Id setter

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName($name): Category
    {
        $this->name = $name;
        return $this;
    }

    public function update(Category $category)
    {
        $this->name = $category->getName();
    }
}

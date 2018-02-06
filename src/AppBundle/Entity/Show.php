<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 05/02/18
 * Time: 16:26
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Show
 * @package AppBundle\Entity
 * @ORM\Entity
 */
class Show
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column
     */
    private $name;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var string
     * @ORM\Column
     */
    private $abstract;

    /**
     * @var string
     * @ORM\Column
     */
    private $country;

    /**
     * @var string
     * @ORM\Column
     */
    private $author;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @var string
     * @ORM\Column
     */
    private $mainPicture;

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
     * @return Show
     */
    public function setName(string $name): Show
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Show
     */
    public function setCategory(Category $category): Show
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    /**
     * @param string $abstract
     * @return Show
     */
    public function setAbstract(string $abstract): Show
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Show
     */
    public function setCountry(string $country): Show
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return Show
     */
    public function setAuthor(string $author): Show
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime $releaseDate
     * @return Show
     */
    public function setReleaseDate(\DateTime $releaseDate): Show
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    /**
     * @param string $mainPicture
     * @return Show
     */
    public function setMainPicture(string $mainPicture): Show
    {
        $this->mainPicture = $mainPicture;
        return $this;
    }

}
<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Show
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShowRepository")
 * @ORM\Table(name="s_show")
 */
class Show
{
    const DATA_SOURCE_DB = "Local database";
    const DATA_SOURCE_OMDB = "OMDB API";

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
     * @Assert\NotBlank(message="Please provide a name for the show", groups={"create", "update"})
     */
    private $name;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Please provide a category for the show", groups={"create", "update"})
     */
    private $category;

    /**
     * @var string
     * @ORM\Column
     * @Assert\NotBlank(message="Please provide an abstract for the show", groups={"create", "update"})
     */
    private $abstract;

    /**
     * @var string
     * @ORM\Column
     * @Assert\NotBlank(message="Please provide a country for the show", groups={"create", "update"})
     */
    private $country;

    /**
     * @var string
     * @ORM\Column
     * @Assert\NotBlank(message="Please provide an author name for the show", groups={"create", "update"})
     */
    private $author;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Please provide a release date for the show", groups={"create", "update"})
     */
    private $releaseDate;

    /**
     * @var string
     * @ORM\Column
     * @Assert\Image(minWidth="200", minHeight="200", groups={"create"})
     */
    private $mainPicture;

    /**
     * @var File
     */
    private $tmpPictureFile;

    /**
     * @var string
     * @ORM\Column
     */
    private $dataSource;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Show
    {
        $this->id = $id;
        return $this;
    }

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
     *
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * @param $mainPicture
     * @return Show
     */
    public function setMainPicture($mainPicture): Show
    {
        $this->mainPicture = $mainPicture;
        return $this;
    }

    /**
     * @return File
     */
    public function getTmpPictureFile(): ?File
    {
        return $this->tmpPictureFile;
    }

    /**
     * @param File $tmpPictureFile
     * @return Show
     */
    public function setTmpPictureFile(File $tmpPictureFile): Show
    {
        $this->tmpPictureFile = $tmpPictureFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataSource(): string
    {
        return $this->dataSource;
    }

    /**
     * @param string $dataSource
     * @return Show
     */
    public function setDataSource(string $dataSource): Show
    {
        $this->dataSource = $dataSource;
        return $this;
    }
}

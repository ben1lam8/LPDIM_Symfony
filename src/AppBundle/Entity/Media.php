<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Media
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="media")
 * @JMS\ExclusionPolicy("all")
 */
class Media
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Expose
     */
    private $id;

    /**
     * @var UploadedFile
     * @ORM\Column
     * @Assert\NotBlank(message="Please provide a file")
     * @JMS\Expose
     */
    private $file;

    /**
     * @var string
     * @ORM\Column
     */
    private $filePath;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    // No Id setter

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return $this
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     * @return Media
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }


}
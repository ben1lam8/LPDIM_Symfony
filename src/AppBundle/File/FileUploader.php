<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 06/02/18
 * Time: 15:14
 */

namespace AppBundle\File;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $pathToProject;
    private $uploadDir;

    public function __construct($pathToProject, $uploadDir)
    {
        $this->pathToProject = $pathToProject;
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $file, $salt)
    {

        $generatedFileName = time().'_'.$salt.'.'.$file->guessClientExtension();
        $path = $this->pathToProject.'/web'.$this->uploadDir;

        $file->move($path, $generatedFileName);
        return $generatedFileName;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 05/02/18
 * Time: 16:26
 */

namespace AppBundle\Entity;


class Show
{

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var string
     */
    private $abstract;
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $author;
    /**
     * @var releaseDate
     */
    private $releaseDate;
    /**
     * @var string
     */
    private $mainPicture;

}
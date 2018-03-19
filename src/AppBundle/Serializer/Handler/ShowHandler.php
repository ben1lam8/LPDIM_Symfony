<?php


namespace AppBundle\Serializer\Handler;

use AppBundle\Entity\Show;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Class ShowHandler
 * @package AppBundle\Serializer\Handler
 */
class ShowHandler implements SubscribingHandlerInterface
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage){
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribingMethods()
    {
        return [
            'direction' =>  GraphNavigator::DIRECTION_DESERIALIZATION,
            'format'    =>  'json',
            'type'      =>  'ABundle\Entity\Show',
            'method'    =>  'deserialize'
        ];
    }

    public function deserialize(JsonDeserializationVisitor $visitor, $data){

        $show = new Show();

        $show
            ->setName($data['name'])
            ->setAbstract($data['abstract'])
            ->setCountry($data['country'])
            ->setReleaseDate($data['release_date'])
        ;
        //...
    }

}
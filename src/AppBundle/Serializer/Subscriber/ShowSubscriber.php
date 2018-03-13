<?php


namespace AppBundle\Serializer\Subscriber;

use AppBundle\Entity\Show;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class ShowSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private static $entityManager;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_DESERIALIZE,
                'format' => 'json',
                'class' => 'AppBundle\Entity\Show',
                'method' => 'onPostDeserialize',
            ]
        ];
    }

    public static function onPostDeserialize(ObjectEvent $objectEvent)
    {
        /**
         * @var Show
         */
        $deserializedShow = $objectEvent->getObject();

        if ($deserializedShow->getCategory() != null) {
            $showCategory = self::$entityManager->getRepository('AppBundle:Category')->find($deserializedShow->getCategory()->getId());

            $deserializedShow->setCategory($showCategory);
        }

        if ($deserializedShow->getAuthor() != null) {
            $showAuthor = self::$entityManager->getRepository('AppBundle:User')->find($deserializedShow->getAuthor()->getId());

            $deserializedShow->setAuthor($showAuthor);
        }
    }

    public static function setEntityManager(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }
}

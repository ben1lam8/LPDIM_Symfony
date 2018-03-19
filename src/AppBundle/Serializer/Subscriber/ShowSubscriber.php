<?php


namespace AppBundle\Serializer\Subscriber;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShowSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

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

    public function onPostDeserialize(ObjectEvent $objectEvent)
    {
        $deserializedShow = $objectEvent->getObject();

        if ($deserializedShow->getCategory() != null) {
            $showCategory = $this->entityManager->getRepository('AppBundle:Category')->find($deserializedShow->getCategory()->getId());

            $deserializedShow->setCategory($showCategory);
        }

        $showAuthor = $this->tokenStorage->getToken()->getUser();
        $deserializedShow->setAuthor($showAuthor);
    }
}

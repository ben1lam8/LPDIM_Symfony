<?php


namespace AppBundle\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{

    const EXCEPTION_HEADER = "The server encountered a problem...";

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION =>  ['processApiException', 1]
        ];
    }

    public function processApiException(GetResponseForExceptionEvent $exceptionEventResponse)
    {
        if(!strpos($exceptionEventResponse->getRequest()->attributes->get('_route'), "api") === 0){
            return;
        }

        //TODO: Define an object and constants
        //TODO: Avoid returning plain Symfony Exception message. Prefer a reformatted one
        $apiException = [
            'header'    =>  self::EXCEPTION_HEADER,
            'message'   =>  $exceptionEventResponse->getException()->getMessage()
        ];

        $exceptionEventResponse->setResponse(
            new JsonResponse(
                $apiException,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application\json']
            )
        );
    }
}
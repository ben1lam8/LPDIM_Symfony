<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\Show;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShowController
 * @package AppBundle\Controller\API
 * @Route(name="api_show_")
 */
class ShowController extends AbstractAPIController
{
    /**
     * @Route("/shows", name="index")
     * @Method({"GET"})
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function indexAction(SerializerInterface $serializer): Response
    {
        $shows = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show')->findAll();

        $serializationGroups = SerializationContext::create()->setGroups(['show']);

        return $this->respondWithJson(
            new Response(
                $serializer->serialize(
                    $shows,
                    'json',
                $serializationGroups
                )
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/show/{id}", name="show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @param SerializerInterface $serializer
     * @param Show $show
     * @return Response
     */
    public function showAction(SerializerInterface $serializer, Show $show): Response
    {
        $serializationGroups = SerializationContext::create()->setGroups(['show']);

        return $this->respondWithJson(
            new Response(
                $serializer->serialize(
                    $show,
                    'json',
                    $serializationGroups
                )
            ),
            Response::HTTP_OK
        );
    }
}

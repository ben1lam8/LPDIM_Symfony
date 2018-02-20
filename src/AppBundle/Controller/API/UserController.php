<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\User;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller\API
 * @Route(name="api_user_")
 */
class UserController extends AbstractAPIController
{
    /**
     * @Route("/users", name="index")
     * @Method({"GET"})
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function indexAction(SerializerInterface $serializer): Response
    {
        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();

        $serializationGroups = SerializationContext::create()->setGroups(['user']);

        return $this->respondWithJson(
            new Response(
                $serializer->serialize(
                    $users,
                    'json',
                    $serializationGroups
                )
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/user/{id}", name="show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @param SerializerInterface $serializer
     * @param User $user
     * @return Response
     */
    public function showAction(SerializerInterface $serializer, User $user): Response
    {
        $serializationGroups = SerializationContext::create()->setGroups(['user']);

        return $this->respondWithJson(
            new Response(
                $serializer->serialize(
                    $user,
                    'json',
                    $serializationGroups
                )
            ),
            Response::HTTP_OK
        );
    }
}

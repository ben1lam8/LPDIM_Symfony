<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @Route("/users", name="create")
     * @Method({"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EncoderFactoryInterface $encoderFactory
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory): Response
    {
        $deserializationGroups = DeserializationContext::create()->setGroups(['user', 'user_create']);

        $user = $serializer->deserialize($request->getContent(), User::class, 'json', $deserializationGroups);

        $constraintValidationList = $validator->validate($user);

        if($constraintValidationList->count() === 0)
        {
            $em = $this->getDoctrine()->getManager();

            $encoder = $encoderFactory->getEncoder($user);
            $hashedPassword = $encoder->encodePassword($user->getPassword(), null);

            $user->setPassword($hashedPassword);
            $user->setRoles(explode(',', $user->getRoles()));

            $em->persist($user);
            $em->flush();

            return $this->respondWithJson('User Created', Response::HTTP_CREATED);
        }

        return $this->respondWithJson(
            new Response(
                $serializer->serialize(
                    $constraintValidationList,
                    'json'
                )
            ),
            Response::HTTP_BAD_REQUEST
        );
    }
}

<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as Doc;
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
     * @Route("/user", name="index")
     * @Method({"GET"})
     * @Doc\Tag(name="users")
     * @Doc\Response(
     *     response=200,
     *     description="Return all the users details",
     *     @Doc\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"user_show"})
     *     )
     * )
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function indexAction(SerializerInterface $serializer): Response
    {
        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();

        $serializationGroups = SerializationContext::create()->setGroups(['user_show']);

        return $this->respondWithJson(
            $serializer->serialize(
                $users,
                'json',
                $serializationGroups
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/user/{id}", name="show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @Doc\Tag(name="users")
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the user to be detailed"
     * )
     * @Doc\Response(
     *     response=200,
     *     description="Return a user details",
     *     @Doc\Schema(
     *         @Model(type=User::class, groups={"user_show"})
     *     )
     * )
     * @Doc\Response(
     *     response=404,
     *     description="No user matches this id",
     * )
     * @param SerializerInterface $serializer
     * @param User $user
     * @return Response
     */
    public function showAction(SerializerInterface $serializer, User $user): Response
    {
        $serializationGroups = SerializationContext::create()->setGroups(['user_show']);

        return $this->respondWithJson(
            $serializer->serialize(
                $user,
                'json',
                $serializationGroups
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/user", name="create")
     * @Method({"POST"})
     * @Doc\Tag(name="users")
     * @Doc\Parameter(
     *      name="user",
     *      in="body",
     *      type="json",
     *      description="data for the user to be created",
     *      required=false,
     *      @Doc\Schema(
     *          @Model(type=User::class, groups={"user_create"})
     *      )
     * )
     * @Doc\Response(
     *     response=201,
     *     description="The user has been successfully created",
     * )
     * @Doc\Response(
     *     response=400,
     *     description="The user couldn't have been created, due to following validation errors.",
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EncoderFactoryInterface $encoderFactory
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory): Response
    {
        $deserializationGroups = DeserializationContext::create()->setGroups(['user_show', 'user_create']);

        $user = $serializer->deserialize($request->getContent(), User::class, 'json', $deserializationGroups);

        $constraintViolationList = $validator->validate($user);

        if ($constraintViolationList->count() === 0) {
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
            $serializer->serialize(
                $constraintViolationList,
                'json'
            ),
            Response::HTTP_BAD_REQUEST
        );
    }
}

<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\Show;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ShowController
 * @package AppBundle\Controller\API
 * @Route(name="api_show_")
 */
class ShowController extends AbstractAPIController
{
    /**
     * @Route("/show", name="index")
     * @Method({"GET"})
     * @Doc\Tag(name="shows")
     * @Doc\Response(
     *     response=200,
     *     description="Returns all the shows details",
     *     @Doc\Schema(
     *         type="array",
     *         @Model(type=Show::class, groups={"show_show"})
     *     )
     * )
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function indexAction(SerializerInterface $serializer): Response
    {
        $shows = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show')->findAll();

        $serializationGroups = SerializationContext::create()->setGroups(['show_show']);

        return $this->respondWithJson(
            $serializer->serialize(
                $shows,
                'json',
                $serializationGroups
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/show/{id}", name="show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @Doc\Tag(name="shows")
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the show to be detailed"
     * )
     * @Doc\Response(
     *     response=200,
     *     description="Returns a show details",
     *     @Doc\Schema(
     *         @Model(type=Show::class, groups={"show_show"})
     *     )
     * )
     * @Doc\Response(
     *     response=404,
     *     description="No show matches this id",
     * )
     * @param Show $show
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function showAction(Show $show, SerializerInterface $serializer): Response
    {
        if (!$show) {
            return $this->respondWithJson(
                $serializer->serialize(
                    null,
                    'json'
                ),
                Response::HTTP_NOT_FOUND
            );
        }

        $serializationGroups = SerializationContext::create()->setGroups(['show_show']);

        return $this->respondWithJson(
            $serializer->serialize(
                $show,
                'json',
                $serializationGroups
            ),
            Response::HTTP_OK

        );
    }

    /**
     * @Route("/show", name="create")
     * @Method({"POST"})
     * @Doc\Tag(name="shows")
     * @Doc\Parameter(
     *      name="show",
     *      in="body",
     *      type="json",
     *      description="data for the show to be created",
     *      required=false,
     *      @Doc\Schema(
     *          @Model(type=Show::class, groups={"show_update"})
     *      )
     * )
     * @Doc\Response(
     *     response=201,
     *     description="The show has been successfully created",
     * )
     * @Doc\Response(
     *     response=400,
     *     description="The show couldn't have been created, due to following validation errors.",
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $sentShow = $serializer->deserialize($request->getContent(), Show::class, 'json');

        $constraintViolationList = $validator->validate($sentShow);

        if ($constraintViolationList->count() === 0) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($sentShow);
            $em->flush();

            return $this->respondWithJson(
                $serializer->serialize(
                    'Show Created',
                    'json'
                ),
                Response::HTTP_CREATED
            );
        }

        return $this->respondWithJson(
            $serializer->serialize(
                $constraintViolationList,
            'json'
            ),
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/show/{id}", name="update", requirements={"id"="\d+"})
     * @Method({"PUT", "PATCH"})
     * @Doc\Tag(name="shows")
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the show to be updated"
     * )
     * @Doc\Parameter(
     *      name="show",
     *      in="body",
     *      type="json",
     *      description="data for the show to be updated",
     *      required=false,
     *      @Doc\Schema(
     *          @Model(type=Show::class, groups={"show_update"})
     *      )
     * )
     * @Doc\Response(
     *     response=204,
     *     description="The show has been successfully updated",
     * )
     * @Doc\Response(
     *     response=400,
     *     description="The show couldn't have been created, due to following validation errors.",
     * )
     * @Doc\Response(
     *     response=404,
     *     description="No show matches this id",
     * )
     * @param Show $show
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function updateAction(Show $show, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        if (!$show) {
            return $this->respondWithJson(
                $serializer->serialize(
                    null,
                    'json'
                ),
                Response::HTTP_NOT_FOUND
            );
        }

        $deserializationGroups = DeserializationContext::create()->setGroups(['show_update']);

        $sentShow = $serializer->deserialize($request->getContent(), Show::class, 'json', $deserializationGroups);

        $constraintsViolationList = $validator->validate($sentShow);

        if ($constraintsViolationList->count() === 0) {
            $em = $this->getDoctrine()->getManager();

            $show->update($sentShow);
            $em->flush();

            return $this->respondWithJson(
                $serializer->serialize(
                    'Show updated',
                'json'
                ),
                Response::HTTP_NO_CONTENT
            );
        }

        return $this->respondWithJson(
                $serializer->serialize(
                $constraintsViolationList,
            'json'
            ),
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/show/{id}", name="delete", requirements={"id"="\d+"})
     * @Method({"DELETE"})
     * @Doc\Tag(name="shows")
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the show to be deleted"
     * )
     * @Doc\Response(
     *     response=204,
     *     description="The show has been successfully deleted",
     * )
     * @Doc\Response(
     *     response=404,
     *     description="No show matches this id",
     * )
     * @param Show $show
     * @return Response
     */
    public function deleteAction(Show $show, SerializerInterface $serializer): Response
    {
        if (!$show) {
            return $this->respondWithJson(
                $serializer->serialize(
                    null,
                    'json'
                ),
                Response::HTTP_NOT_FOUND
            );
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($show);
        $em->flush();

        return $this->respondWithJson(
            $serializer->serialize(
                'Show deleted',
                'json'
            ),
            Response::HTTP_NO_CONTENT
        );
    }
}

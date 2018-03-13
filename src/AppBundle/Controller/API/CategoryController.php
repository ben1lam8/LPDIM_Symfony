<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\Category;
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
 * @Route(name="api_category_")
 */
class CategoryController extends AbstractAPIController
{
    /**
     * @Route("/category", name="index")
     * @Method({"GET"})
     * @Doc\Tag(name="categories")
     * @Doc\Response(
     *     response=200,
     *     description="Return all the categories details",
     *     @Doc\Schema(
     *         type="array",
     *         @Model(type=Category::class, groups={"category_show"})
     *     )
     * )
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function indexAction(SerializerInterface $serializer): Response
    {
        $categories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category')->findAll();

        $serializationGroups = SerializationContext::create()->setGroups(['category_show']);

        return $this->respondWithJson(
            $serializer->serialize(
                $categories,
                'json',
                $serializationGroups
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/category/{id}", name="show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @Doc\Tag(name="categories")
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the show to be detailed"
     * )
     * @Doc\Response(
     *     response=200,
     *     description="Return a category details",
     *     @Doc\Schema(
     *         @Model(type=Category::class, groups={"category_show"})
     *     )
     * )
     * @Doc\Response(
     *     response=404,
     *     description="No category matches this id",
     * )
     * @param SerializerInterface $serializer
     * @param Category $category
     * @return Response
     */
    public function showAction(SerializerInterface $serializer, Category $category): Response
    {
        if (!$category) {
            return $this->respondWithJson(
                $serializer->serialize(
                    null,
                    'json'
                ),
                Response::HTTP_NOT_FOUND
            );
        }

        $serializationGroups = SerializationContext::create()->setGroups(['category_show']);

        return $this->respondWithJson(
            $serializer->serialize(
                $category,
                'json',
                $serializationGroups
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/category", name="create")
     * @Method({"POST"})
     * @Doc\Tag(name="categories")
     * @Doc\Parameter(
     *      name="category",
     *      in="body",
     *      type="json",
     *      description="data for the category to be created",
     *      required=false,
     *      @Doc\Schema(
     *          @Model(type=Category::class, groups={"category_update"})
     *      )
     * )
     * @Doc\Response(
     *     response=201,
     *     description="The category has been successfully created",
     * )
     * @Doc\Response(
     *     response=400,
     *     description="The category couldn't have been created, due to following validation errors.",
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $deserializationGroups = DeserializationContext::create()->setGroups(['category_update']);

        $category = $serializer->deserialize($request->getContent(), Category::class, 'json', $deserializationGroups);

        $constraintsViolationsList = $validator->validate($category);

        if ($constraintsViolationsList->count() === 0) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->respondWithJson('Category Created', Response::HTTP_CREATED);
        }

        return $this->respondWithJson(
            $serializer->serialize(
                $constraintsViolationsList,
                'json'
            ),
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/category/{id}", name="update")
     * @Method({"PUT", "PATCH"})
     * @Doc\Tag(name="categories")
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the category to be updated"
     * )
     * @Doc\Parameter(
     *      name="category",
     *      in="body",
     *      type="json",
     *      description="data for the category to be updated",
     *      required=false,
     *      @Doc\Schema(
     *          @Model(type=Category::class, groups={"category_update"})
     *      )
     * )
     * @Doc\Response(
     *     response=204,
     *     description="The category has been successfully updated",
     * )
     * @Doc\Response(
     *     response=400,
     *     description="The category couldn't have been created, due to following validation errors.",
     * )
     * @Doc\Response(
     *     response=404,
     *     description="No category matches this id",
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Category $storedCategory
     * @return Response
     */
    public function updateAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Category $storedCategory): Response
    {
        if (!$storedCategory) {
            return $this->respondWithJson(
                $serializer->serialize(
                    null,
                    'json'
                ),
                Response::HTTP_NOT_FOUND
            );
        }

        $sentCategory = $serializer->deserialize($request->getContent(), Category::class, 'json');

        $constraintsViolationsList = $validator->validate($sentCategory);

        if ($constraintsViolationsList->count() === 0) {
            $em = $this->getDoctrine()->getManager();
            $storedCategory->update($sentCategory);

            $em->flush();

            return $this->respondWithJson('Category Updated', Response::HTTP_OK);
        }

        return $this->respondWithJson(
            $serializer->serialize(
                $constraintsViolationsList,
                'json'
            ),
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/category/{id}", name="delete")
     * @Method({"DELETE"})
     * @Doc\Tag(name="categories")
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The id of the category to be deleted"
     * )
     * @Doc\Response(
     *     response=204,
     *     description="The category has been successfully deleted",
     * )
     * @Doc\Response(
     *     response=404,
     *     description="No category matches this id",
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Category $storedCategory
     * @return Response
     */
    public function deleteAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Category $storedCategory): Response
    {
        if (!$storedCategory) {
            return $this->respondWithJson(
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($storedCategory);
        $em->flush();

        return $this->respondWithJson(
            'Category deleted',
            Response::HTTP_NO_CONTENT
        );
    }
}

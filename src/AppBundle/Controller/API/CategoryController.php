<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\Category;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
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
     * @Route("/categories", name="index")
     * @Method({"GET"})
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function indexAction(SerializerInterface $serializer): Response
    {
        $categories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category')->findAll();

        $serializationGroups = SerializationContext::create()->setGroups(['category']);

        return $this->respondWithJson(
            new Response(
                $serializer->serialize(
                    $categories,
                    'json',
                    $serializationGroups
                )
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/category/{id}", name="show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @param SerializerInterface $serializer
     * @param Category $category
     * @return Response
     */
    public function showAction(SerializerInterface $serializer, Category $category): Response
    {
        $serializationGroups = SerializationContext::create()->setGroups(['category']);

        return $this->respondWithJson(
            new Response(
                $serializer->serialize(
                    $category,
                    'json',
                    $serializationGroups
                )
            ),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/categories", name="create")
     * @Method({"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json');

        $constraintValidationList = $validator->validate($category);

        if($constraintValidationList->count() === 0)
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->respondWithJson('Category Created', Response::HTTP_CREATED);
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

    /**
     * @Route("/category/{id}", name="update")
     * @Method({"PUT"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Category $storedCategory
     * @return Response
     */
    public function updateAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Category $storedCategory): Response
    {
        $sentCategory = $serializer->deserialize($request->getContent(), Category::class, 'json');

        $constraintValidationList = $validator->validate($sentCategory);

        if($constraintValidationList->count() === 0)
        {
            $em = $this->getDoctrine()->getManager();
            $storedCategory->update($sentCategory);

            $em->flush();

            return $this->respondWithJson('Category Updated', Response::HTTP_OK);
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

    /**
     * @Route("/category/{id}", name="delete")
     * @Method({"DELETE"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Category $storedCategory
     * @return Response
     */
    public function deleteAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Category $storedCategory): Response
    {
        // TODO :
    }
}

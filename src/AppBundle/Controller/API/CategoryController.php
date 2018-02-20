<?php


namespace AppBundle\Controller\API;

use AppBundle\Entity\Category;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

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
}

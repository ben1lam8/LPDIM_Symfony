<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Type\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 * @Route("/category", name="category_")
 */
class CategoryController extends Controller
{

    /**
     * @Route("/create", name="create")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category successfully created !');

            return $this->redirectToRoute('show_index');
        }

        return $this->render(
            'category/create.html.twig',
            ['categoryForm' => $form->createView()]
        );
    }

    public function menuAction(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render("_includes/categories.html.twig", [
            'categories' => $categories
        ]);
    }
}

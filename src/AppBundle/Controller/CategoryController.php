<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 06/02/18
 * Time: 12:00
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Type\CategoryType;
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
     * @param Request $request
     * @Route("/new", name="new")
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category successfully created !');

            return $this->redirectToRoute('show_list');
        }

        return $this->render(
            'category/new.html.twig',
            ['categoryForm' => $form->createView()]
        );
    }
}
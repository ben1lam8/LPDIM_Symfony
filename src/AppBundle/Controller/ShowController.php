<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 05/02/18
 * Time: 13:54
 */

namespace AppBundle\Controller;


use AppBundle\Type\ShowType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestController
 * @package AppBundle\Controller
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/", name="list")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request) : Response
    {
        return $this->render("show/list.html.twig", []);

    }

    /**
     * @Route("/new", name="new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request) : Response
    {
        $form = $this->createForm(ShowType::class);

        return $this->render(
            "show/new.html.twig",
            ['showForm' => $form->createView()]
        );
    }

    public function categoriesAction() : Response
    {
        return $this->render("_includes/categories.html.twig",[
            'categories' => ['Web Design', 'HTML', 'Freebies', 'Javascript', 'CSS', 'Tutorials']
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 05/02/18
 * Time: 13:54
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Show;
use AppBundle\File\FileUploader;
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
        $shows = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show')->findAll();

        return $this->render("show/list.html.twig", ['shows' => $shows]);

    }

    /**
     * @Route("/new", name="new")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function newAction(Request $request, FileUploader $fileUploader): Response
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show, ['validation_groups' => 'create']);

        $form->handleRequest($request);

        if($form->isValid()){

            $generatedFileName = $fileUploader->upload(
                $show->getMainPicture(),
                $show->getCategory()->getName()
            );

            $show->setMainPicture($generatedFileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash('success', 'Show successfully created !');

            return $this->redirectToRoute('show_list');
        }

        return $this->render(
            "show/new.html.twig",
            ['showForm' => $form->createView()]
        );
    }

    /**
     * @Route("/update", name="update")
     * @param Show $show
     * @return Response
     */
    public function updateAction(Request $request, Show $show): Response
    {

        $showForm = $this->createForm(ShowType::class, $show, ['validation_groups' => 'update']);

        $showForm->handleRequest($request);

        if($showForm->isValid()){

            //TODO : Use FileUploader to remove tmp/main picture

            $this->addFlash('success', 'Show successfully updated !');

            return $this->redirectToRoute('show_list');
        }

        return $this->render(
            'show/new.html.twig',
            ['showForm' => $showForm->createView()]
        );
    }

    public function categoriesAction() : Response
    {
        return $this->render("_includes/categories.html.twig",[
            'categories' => ['Web Design', 'HTML', 'Freebies', 'Javascript', 'CSS', 'Tutorials']
        ]);
    }
}
<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\File\FileUploader;
use AppBundle\Type\ShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/", name="index")
     * @Method({"GET"})
     * @return Response
     */
    public function indexAction(): Response
    {
        $shows = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show')->findAll();

        return $this->render(
            "show/index.html.twig",
            ['shows' => $shows]
        );
    }

    /**
     * @Route("/show/{id}", name="show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @param Show $show
     * @return Response
     */
    public function showAction(Show $show): Response
    {
        return $this->render(
            "show/show.html.twig",
            ['show' => $show]
        );
    }

    /**
     * @Route("/create", name="create")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function createAction(Request $request, FileUploader $fileUploader): Response
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show, ['validation_groups' => 'create']);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $generatedFileName = $fileUploader->upload(
                $show->getMainPicture(),
                $show->getCategory()->getName()
            );

            $show->setMainPicture($generatedFileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash('success', 'Show successfully created !');

            return $this->redirectToRoute('show_index');
        }

        return $this->render(
            "show/create.html.twig",
            ['showForm' => $form->createView()]
        );
    }

    /**
     * @Route("/update/{id}", name="update", requirements={"id"="\d+"})
     * @Method({"GET", "PUT"})
     * @param Request $request
     * @param Show $show
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function updateAction(Request $request, Show $show, FileUploader $fileUploader): Response
    {
        $showForm = $this->createForm(ShowType::class, $show, ['validation_groups' => 'update']);

        $showForm->handleRequest($request);

        if ($showForm->isValid()) {
            $generatedFileName = $fileUploader->upload(
                $show->getMainPicture(),
                $show->getCategory()->getName()
            );

            $show->setMainPicture($generatedFileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash('success', 'Show successfully updated !');

            return $this->redirectToRoute('show_index');
        }

        return $this->render(
            'show/create.html.twig',
            ['showForm' => $showForm->createView()]
        );
    }

    public function searchAction(): Response
    {
    }
}

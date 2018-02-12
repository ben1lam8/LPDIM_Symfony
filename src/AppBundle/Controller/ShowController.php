<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\File\FileUploader;
use AppBundle\Type\ShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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
    public function indexAction(Request $request): Response
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show');
        $session = $request->getSession();

        if($session->has('query_search_shows')){
            $showsQuery = $session->get('query_search_shows');
            $shows = $repo->findAllByQuery($showsQuery);

            $session->remove('query_search_shows');
        }else{
            $shows = $repo->findAll();
        }

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
                $show->getTmpPictureFile(),
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
        $showForm = $this->createForm(
            ShowType::class,
            $show,
            ['validation_groups' => 'update',
            'method' => 'PUT']
        );

        $showForm->handleRequest($request);

        if ($showForm->isValid()) {
            $generatedFileName = $fileUploader->upload(
                $show->getTmpPictureFile(),
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

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id"="\d+"})
     * @Method({"DELETE"})
     * @param Request $request
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @return Response
     */
    public function deleteAction(Request $request, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $doctrine = $this->getDoctrine();

        $showId = $request->request->get('show_id');

        $show = $doctrine->getRepository('AppBundle:Show')->findOneById($showId);

        if(!$show){
            throw new NotFoundHttpException("No show matching the id %d", $showId);
        }

        $csrfToken = new CsrfToken('delete_show', $request->request->get('_csrf_token'));

        if($csrfTokenManager->isTokenValid($csrfToken)){
            $doctrine->getManager()->remove($show);
            $doctrine->getManager()->flush();

            $this->addFlash("success", "Show successfully deleted !");
        }else{
            $this->addFlash("danger", "Show not deleted. Invalid CSRF token !");
        }

        return $this->redirectToRoute("show_index");
    }

    /**
     * @Route("/search", name="search")
     * @Method({"DELETE"})
     * @return Response
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request): Response
    {
        $request->getSession()->set('query_search_shows', $request->request->get('query'));
        return $this->redirectToRoute('show_index');
    }
}

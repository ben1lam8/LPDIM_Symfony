<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user", name="user_")
 */
class UserController extends Controller
{

    //TODO: updateAction, deleteAction, etc. + beware of granted accesses
    // via options ? monter type en service avec injections ?

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function indexAction(): Response
    {
        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();

        return $this->render(
            "user/index.html.twig",
            ['users' => $users]
        );
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     * @return Response
     */
    public function createAction(Request $request, EncoderFactoryInterface $encoderFactory): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $encoder = $encoderFactory->getEncoder($user);
            $hashedPassword = $encoder->encodePassword($user->getPassword(), null);

            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em-> flush();

            $this->addFlash("success", "User successfully created !");

            return $this->redirectToRoute("show_index");
        }

        return $this->render(
            "user/create.html.twig",
            ["userForm" => $userForm->createView()]
        );
    }
}

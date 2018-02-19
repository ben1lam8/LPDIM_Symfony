<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user", name="user_")
 */
class UserController extends Controller
{

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
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

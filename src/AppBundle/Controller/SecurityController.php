<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 * * @Route(name="security_")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function loginAction(AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            ['lastUsername' => $lastUsername,
            'error'         => $error]
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // Hollow check action...
        // Try yml routing if it's too ugly for you
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // Hollow logout action...
        // Try yml routing if it's too ugly for you
    }

    //TODO : Add a complete implementation for a ROLE_ADMIN
}

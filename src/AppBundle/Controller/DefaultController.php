<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route(
     *     "/test/{username}",
     *     name="homepage",
     *     requirements={
     *         "username"=".*",
     *          },
     *     schemes={"http", "https"}
     *     )
     * @Method(
     *     {"GET"}
     *     )
     */
    public function indexAction(Request $request, $username)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'username' => $username
        ]);
    }
}

<?php


namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAPIController extends Controller
{
    protected function respondWithJson($data, $statusCode): Response
    {
        return new Response($data, $statusCode, ['Content-Type' => 'application\json']);
    }
}

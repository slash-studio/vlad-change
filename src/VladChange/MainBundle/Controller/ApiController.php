<?php

namespace VladChange\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    public function addPlacemarkAction($x, $y)
    {
        $response = new JsonResponse();
        $response->setData(array(
            'x' => $x,
            'y' => $y
        ));
        return $response;
    }
}

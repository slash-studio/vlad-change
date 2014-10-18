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

    public function getPlacemarkInfoAction($id)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository('VladChangeStoreBundle:Placemark')->getFullInfo($id)
        );
    }

    public function getAllPlacemarkAction()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository('VladChangeStoreBundle:Placemark')->getCurrentPlacemarks()
        );
    }
}

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

    public function getAllPlacemarkAction()
    {
        $response = new JsonResponse();
        $response->setData(array(
            array('x' => "43.13416130704415", 'y' => "131.9348007202148", 'short_desc' => 'fbsql_rows_fetched(result)'),
            array('x' => "43.0853622332054", 'y' => "131.91969451904296", 'short_desc' => 'qweavvwevwvwvw21312'),
            array('x' =>"43.117563958165064",'y' =>"131.94166717529296", 'short_desc' => 'qwertyui')
        ));
        return $response;
    }
}

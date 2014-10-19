<?php

namespace VladChange\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    public function addLikeAction($id, $type, $action)
    {
        $response = new JsonResponse('Not found', JsonResponse::HTTP_NOT_FOUND);
        $user = $this->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $project = $em->getRepository('VladChangeStoreBundle:Placemark')->findOneById($id);
        $userProj = $project->getUser();
        if (empty($user) || (!empty($userProj) && $userProj->getId() == $user->getId())) return $response;
        $f = sprintf('remove%s', ucfirst($action));
        $user->$f($project);
        if ($type == 'add') {
            $f = sprintf('add%s', ucfirst($action));
            $user->$f($project);
        }
        $em->persist($user);
        $em->flush();
        // if ($action == 'like') {
        //     $user->removeLike($project);
        //     if ($type == 'add') {
        //         $user->addLike($project);
        //     }
        //     $em->persist($user);
        //     $em->flush();
        // } else {
        //     $user->removeDislike($project);
        //     if ($type == 'add') {
        //         $user->addDislike($project);
        //     }
        //     $em->persist($user);
        //     $em->flush();
        // }
        return $response->setStatusCode(JsonResponse::HTTP_OK)->setData(['result' => true]);
    }

    public function getPlacemarkInfoAction($id)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository('VladChangeStoreBundle:Placemark')->getFullInfo($id)
        );
    }

    public function getAllPlacemarkAction()
    {
        $u = $this->getUser();
        return new JsonResponse(
            $this->getDoctrine()
                 ->getRepository('VladChangeStoreBundle:Placemark')
                 ->getCurrentPlacemarks(!empty($u) ? $u->getId() : null)
        );
    }
}

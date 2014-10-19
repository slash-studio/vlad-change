<?php

namespace VladChange\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use VladChange\StoreBundle\Entity\Comment;

class ApiController extends Controller
{
    public function addCommentAction(Request $request)
    {
        $response = new JsonResponse('Not found', JsonResponse::HTTP_NOT_FOUND);
        $user = $this->getUser();
        if (empty($user)) return $response;
        $em = $this->getDoctrine()->getEntityManager();
        $project = $em->getRepository('VladChangeStoreBundle:Placemark')->findOneById($request->request->get('project_id', -1));
        if (empty($project)) return $response;
        $comment = new Comment();
        $comment->setUser($user)
                ->setPlacemark($project)
                ->setMessage($request->request->get('message'));
        $em->persist($comment);
        $em->flush();
        return $response->setStatusCode(JsonResponse::HTTP_OK)->setData([
            'result' => true,
            'create_date' => $comment->getDt()->format('d.m.Y H:m'),
            'owner' => [
                'name' => sprintf('%s %s', $user->getName(), $user->getSurname()),
                'id' => $user->getId(),
                'image' => null
            ]
        ]);
    }

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

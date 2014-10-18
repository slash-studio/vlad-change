<?php

namespace VladChange\MainBundle\Controller;

use VladChange\MainBundle\Form\Type\PlacemarkType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VladChangeMainBundle:Pages:index.html.twig');
    }

    public function editProjectAction(Request $request, $id)
    {
        $user = $this->getUser();
        $placemark = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('VladChangeStoreBundle:Placemark')
                          ->findOneById($id);
        if ($placemark == null || $placemark->getUser()->getId() != $user->getId()) {
            return $this->redirect($this->generateUrl('vlad_change_main_homepage'));
        }
        $form = $this->createForm(new PlacemarkType(), $placemark);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($form->getData());
            $em->flush();
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        return $this->render('VladChangeMainBundle:Pages:edit_project.html.twig', [
            'form' => $form->createView(),
            'archived' => $placemark->getArchived(),
            'expired'  => $placemark->isExpired(),
            'notliked' => !$placemark->isLiked()
        ]);
    }

    public function manipulateProjectAction(Request $request, $type)
    {
        $user = $this->getUser();
        $response = new Response('Not found', 404, ['Content-Type' => 'application/json']);
        if (empty($user) || !$request->isXMLHttpRequest()) return $response;
        $em = $this->getDoctrine()->getEntityManager();
        $placemark = $em->getRepository('VladChangeStoreBundle:Placemark')
                        ->findOneById($request->request->get('id'));
        if (empty($placemark)) return $response;
        if ($type == 'archive') {
            $em->merge($placemark->setArchived(true));
        } else {
            $em->remove($placemark);
        }
        $em->flush();
        return $response->setStatusCode(200);;
    }

    public function addProjectAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new PlacemarkType());
        $lat = $request->query->get('lat');
        $lon = $request->query->get('lon');
        if (!empty($lat) && !empty($lon)) {
            $form->get('lat')->setData($lat);
            $form->get('lon')->setData($lon);
        }
        $view = null;
        $isAvailableProj = $user->hasAvailableProjAmount();
        if ($isAvailableProj) {
            $form = $this->createForm(new PlacemarkType());
            $lat = $request->query->get('lat');
            $lon = $request->query->get('lon');
            if (!empty($lat) && !empty($lon)) {
                $form->get('lat')->setData($lat);
                $form->get('lon')->setData($lon);
            }
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $tmp = $form->getData();
                $tmp->setUser($user);
                $em->persist($tmp);
                $em->flush();
                return $this->redirect($this->generateUrl('fos_user_profile_show'));
            }
            $view = $form->createView();
        }
        return $this->render('VladChangeMainBundle:Pages:add_project.html.twig', [
            'form' => $form->createView(),
            'isAvailableProj' => $isAvailableProj
        ]);
    }
}

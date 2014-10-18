<?php

namespace VladChange\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use VladChange\MainBundle\Form\Type\PlacemarkType;
use Symfony\Component\HttpFoundation\Request;

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
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $tmp = $form->getData();
            $tmp->setUser($user);
            $em->persist($tmp);
            $em->flush();
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        return $this->render('VladChangeMainBundle:Pages:add_project.html.twig', [
            'form' => $view,
            'isAvailableProj' => $isAvailableProj
        ]);
    }
}

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

    public function addProjectAction(Request $request)
    {
        $form = $this->createForm(new PlacemarkType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('VladChangeMainBundle:Pages:add_project.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

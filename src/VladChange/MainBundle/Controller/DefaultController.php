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
        $user = $this->getUser();
        if (empty($user)) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $form = $this->createForm(new PlacemarkType());
        $form->handleRequest($request);
        $lat = $request->get('lat');
        $lon = $request->get('lon');
        if (!empty($lat) && !empty($lon)) {
            //set lat and lon if exist
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $tmp = $form->getData();
            $tmp->setUser($user);
            $em->persist($tmp);
            $em->flush();
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('VladChangeMainBundle:Pages:add_project.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

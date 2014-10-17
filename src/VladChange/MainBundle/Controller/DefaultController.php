<?php

namespace VladChange\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VladChangeMainBundle:Pages:index.html.twig');
    }

    public function viewProfileAction($id)
    {
        $userName = $this->get('doctrine')->getManager()
                         ->getRepository('VladChangeStoreBundle:User')
                         ->findOneBy(['id' => $id])
                         ->getUsername();
        return $this->render(
            'VladChangeMainBundle:Default:profile.html.twig',
            ['user_name' => $userName]
        );
    }
}

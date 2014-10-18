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
        $authorized_user = $this->getUser();
        if (!empty($authorized_user) && $authorized_user->getId() == $id) {
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        $user = $this->get('doctrine')
                     ->getManager()
                     ->getRepository('VladChangeStoreBundle:User')
                     ->findOneBy(['id' => $id]);
        if (empty($user)) {
            return $this->redirect($this->generateUrl('vlad_change_main_homepage'));
        }
        return $this->render(
            'VladChangeMainBundle:Default:profile.html.twig',
            ['name' => $user->getName(), 'surname' => $user->getSurname()]
        );
    }
}

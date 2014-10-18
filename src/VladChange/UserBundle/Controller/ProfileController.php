<?php

namespace VladChange\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;

class ProfileController extends BaseController
{
    public function showAction()
    {
        return parent::showAction();
        // $user = $this->getUser();
        // if (!is_object($user) || !$user instanceof UserInterface) {
        //     throw new AccessDeniedException('This user does not have access to this section.');
        // }

        // return $this->render('FOSUserBundle:Profile:show.html.twig', array(
        //     'user' => $user
        // ));
    }
}
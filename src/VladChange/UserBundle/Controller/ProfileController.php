<?php

namespace VladChange\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as BaseController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    public function showAction()
    {
        $id = $this->getRequest()->get('id');
        $user = $this->getUser();
        $isMine = true;
        if (!empty($user) && $user->getId() == $id) {
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        if (!empty($id)) {
            $user = $this->get('doctrine')
                         ->getManager()
                         ->getRepository('VladChangeStoreBundle:User')
                         ->findOneBy(['id' => $id]);
            if (empty($user)) {
                return $this->redirect($this->generateUrl('vlad_change_main_homepage'));
            }
            $isMine = false;
        } elseif (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        return $this->render(
            'FOSUserBundle:Profile:show.html.twig',
            [
                'user'   => $user,
                'isMine' => $isMine
            ]
        );

    }
}

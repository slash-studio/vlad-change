<?php

namespace VladChange\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use \FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{

    protected function renderLogin(array $data)
    {

        if (true === $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
        }

        $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        return $this->container->get('templating')->renderResponse($template, $data);
    }

}

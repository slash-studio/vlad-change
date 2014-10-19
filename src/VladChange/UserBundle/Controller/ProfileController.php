<?php

namespace VladChange\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as BaseController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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
            'FOSUserBundle:Profile:profile.html.twig',
            [
                'user'   => $user,
                'isMine' => $isMine
            ]
        );
    }

    public function editAvatarAction(Request $request)
    {
        // var_dump($request->request->get('data'));
        // exit;
        $user = $this->getUser();

        if (empty($user)) {
            return $this->redirect($this->generateUrl('vlad_change_main_homepage'));
        }
        return $this->render(
            'VladChangeUserBundle:Profile:avatar_upload.html.twig',
            [
                'photo_data' => $request->request->get('data')
            ]
        );
        // exit;
    }


    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'image'=> $user->getImage()
        ));
    }
}

<?php

namespace VladChange\MainBundle\Controller;

use VladChange\MainBundle\Form\Type\PlacemarkType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use VladChange\StoreBundle\Entity\Image;

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
            'notliked' => !$placemark->isLiked(),
            'project_id' => $placemark->getId()
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
            $response->setContent('Archived');
        } else {
            $em->remove($placemark);
            $response->setContent('Deleted');
        }
        $em->flush();
        return $response->setStatusCode(200);
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

    public function getImageAction($id)
    {
        $img = $this->getDoctrine()->getRepository('VladChangeStoreBundle:Image')->findOneById($id);
        if (!$img) {
            return new Response('Not found', 404);
        }
        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($img->getAbsolutePath()));
        $response->setContent(file_get_contents($img->getAbsolutePath()));
        return $response;

    }

    public function uploadImageAction(Request $request)
    {
        $response = new Response('', Response::HTTP_NOT_FOUND, ['Content-Type' => 'text/html']);

        preg_match('/(.*)(\..*)/', basename($_FILES['uploadimage']['name']), $arr);
        $ext        = strtolower($arr[2]);
        $filetypes  = Array('.jpg', '.jpeg', '.png');
        $ajaxResult = Array('result' => true, 'message' => 'Загрузка прошла успешно!', 'file_tmp' => $_FILES['uploadimage']['name']);

        $__file = null;

        try {

           if (!in_array($ext, $filetypes)) {
              throw new Exception('Это разрешение не поддерживается. Только JPG и PNG.');
           }

           $ajaxResult['ext'] = $ext;

           $arr = getimagesize($_FILES['uploadimage']['tmp_name']);
           if ($request->get('width') && $arr[0] < $request->get('width')) {
              throw new Exception('Ширина изображения меньше допустимой!');
           }

           if ($request->get('height') && $arr[1] < $request->get('height')) {
              throw new Exception('Высота изображения меньше допустимой!');
           }

           $ajaxResult['width'] = $arr[0];
           $ajaxResult['height'] = $arr[1];

           if ($_FILES['uploadimage']['size'] > $request->get('maxSize')) {
              throw new Exception('Размер изображения превышает максимальный!');
           }

           if (!file_exists(UPLOAD_DIR)) {
              mkdir(UPLOAD_DIR);
           }

           $img = new Image();
           $img->setExtension($ext);
           $em = $this->getDoctrine()->getEntityManager();
           $em->persist($img);
           $em->flush();
           $__file = $img->getId();

           $path = $img->getAbsolutePath();
           if (!move_uploaded_file($_FILES['uploadimage']['tmp_name'], $path)) {
              throw new Exception('Ошибка при загрузке файла на сервер!');
           }
           $ajaxResult['file'] = $__file;

        } catch (Exception $e) {
           $ajaxResult['result']  = false;
           $ajaxResult['message'] = $e->getMessage();
        }

        if ($ajaxResult['result']) {
           $response->setStatusCode(Response::HTTP_OK);
        }
        $response->setContent(json_encode($ajaxResult))->send();
    }
}

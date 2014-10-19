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

    public function getImageAction($id, $param)
    {

        $img = $this->getDoctrine()->getRepository('VladChangeStoreBundle:Image')->findOneById($id);
        if (!$img) {
            return new Response('Not found', 404);
        }
        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($img->getAbsolutePath2($param)));
        $response->setContent(file_get_contents($img->getAbsolutePath2($param)));
        return $response;
    }

    public function getRootImageAction($id)
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

    public function resizeImageAction(Request $request)
    {
        $response = new Response('Not found', 404, ['Content-Type' => 'application/json']);

        $ajaxResult = ['result' => true];

        function crop_and_resize ($im, $is_png, $x1, $y1, $x2, $y2, $new_width, $new_height) {
           $im_w = abs($x1 - $x2);
           $im_h = abs($y1 - $y2);
           $new_img = imagecreatetruecolor($new_width, $new_height);
           if ($is_png) {
              imagealphablending($new_img, false);
              imagesavealpha($new_img, true);
              imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
           }
           imagecopyresampled($new_img, $im, 0, 0, $x1, $y1, $new_width, $new_height, $im_w, $im_h);
           return $new_img;
        }

        $imId   = $request->get('fileName');
        $image = $this->getDoctrine()->getRepository('VladChangeStoreBundle:Image')->findOneById($imId);
        if (!$image) {
            return new Response('Not found', 404);
        }
        $path         = $image->getAbsolutePath();
        if ($image->getExtension() == '.png') {
           $im        = imagecreatefrompng($path);
        } else {
           $im        = imagecreatefromjpeg($path);
        }
        $arr          = getimagesize($path);
        $owner_width  = $arr[0];
        $owner_height = $arr[1];
        $width        = $request->get('width');
        $height       = $request->get('height');
        $after_resize = $request->get('afterResize');

        $crop_type = $request->request->get('cropType');

        if ($crop_type == 'userCrop') {
            $x1 = $request->request->get('x1');
            $y1 = $request->request->get('y1');
            $x2 = $request->request->get('x2');
            $y2 = $request->request->get('y2');
        } else {
            $x1 = floor(($owner_width - $width) / 2);
            $y1 = floor(($owner_height - $height) / 2);
            $x2 = $x1 + $width;
            $y2 = $y1 + $height;
        }

        $p_sizes   = explode(',', $request->get('sizes'));

        foreach ($p_sizes as $size) {
            $sizes = explode('#', $size);
            $n_name = $sizes[0];
            $n_width = $sizes[1];
            $n_height = $sizes[2];
            $new_img = crop_and_resize(
                $im,
                $image->getExtension() == '.png',
                $x1, $y1, $x2, $y2, $n_width, $n_height
            );
            $n_name = $image->getUploadRootDir() . "/{$imId}_{$n_name}" . $image->getExtension();
            if ($image->getExtension() == '.png') {
              imagepng($new_img, $n_name);
           } else {
              imagejpeg($new_img, $n_name);
           }
        }

        if (isset($after_resize) && $after_resize > 0) {
            if ($owner_width <= $after_resize && $owner_height <= $after_resize) {
                $h = $owner_height;
                $w = $owner_width;
            } else if ($owner_width >= $owner_height) {
              //ширина
                $h = round($after_resize / $owner_width * $owner_height);
                $w = $after_resize;
            } else {
                $w = round($after_resize / $owner_height * $owner_width);
                $h = $after_resize;
            }
            $big = imagecreatetruecolor($w, $h);
            imagecopyresampled($big, $im, 0, 0, 0, 0, $w, $h, $owner_width, $owner_height);
            $b_name = $image->getUploadRootDir() . "/{$imId}_b" . $image->getExtension();
            if ($image->getExtension() == '.png') {
                imagepng($big, $b_name);
            } else {
                imagejpeg($big, $b_name);
            }
            @unlink($image->getAbsolutePath());
            // rename($b_name, $image->getAbsolutePath());
            $image->setResized(true);
            $em = $this->getDoctrine()->getManager();
            $em->merge($image);
            $em->flush();
        }

        if ($ajaxResult['result']) {
           $response->setStatusCode(Response::HTTP_OK);
        }
        $response->setContent(json_encode($ajaxResult))->send();
    }

    public function uploadImageAction(Request $request)
    {
        $response = new Response('Not found', Response::HTTP_NOT_FOUND, ['Content-Type' => 'text/html']);
        if ($request->request->get('isAvatar')) {
            $user = $this->getUser();
            if (empty($user)) {
                return $response;
            }
        }

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
           $user->setImage($img);
           $em->merge($user);
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

<?php

namespace App\Controller;

use App\Form\PhotoUploaderType;
use App\Service\PhotoUploader;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotosController
 * @package App\Controller
 * @Route("/photos")
 */
class PhotosController extends AbstractController
{
    /**
     * @Route("/", name="photos")
     */
    public function index()
    {
        return $this->render('photos/index.html.twig', [
            'controller_name' => 'PhotosController',
            'module' => 'photos',
        ]);
    }

    /**
     * @Route("/p/{galleryID}/{albumID}/{photoID}", name="photoView", defaults={"page"=1})
     * @Route("/p/{galleryID}/{albumID}/{photoID}/{page}", name="photoViewPaged")
     */
    public function viewPhoto()
    {
        return $this->render('photos/viewPhoto.html.twig', [
            'module' => 'photos',
        ]);
    }

    /**
     * @Route("/g/{galleryID}", name="photoViewGallery", defaults={"page"=1})
     * @Route("/g/{galleryID}/{page}", name="photoViewGalleryPaged")
     */
    public function viewGallery()
    {
        return $this->render('photos/viewGallery.html.twig', [
            'module' => 'photos',
        ]);
    }

    /**
     * @Route("/a/{galleryID}/{albumID}", name="photoViewAlbum", defaults={"page"=1})
     * @Route("/a/{galleryID}/{albumID}/{page}", name="photoViewAlbumPaged")
     */
    public function viewAlbum()
    {
        return $this->render('photos/viewAlbum.html.twig', [
            'module' => 'photos',
        ]);
    }

    /**
     * @Route("/upload")
     * @param PhotoUploader $uploaderService
     * @param Request $request
     * @param FilesystemMap $filesystemMap
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function upload(PhotoUploader $uploaderService, Request $request, FilesystemMap $filesystemMap) {
        $form = $this->createForm(PhotoUploaderType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $files = $form->get("images")->getData();

            $adapter = $filesystemMap->get('photos');

            $uploaderService->uploadFiles($files, $adapter);
        } else {
            return $this->render('photos/upload.html.twig', [
                'module' => 'photos',
                'form' => $form->createView()
            ]);
        }

        //$uploaderService->uploadFiles();

        /*
          $adapter = $container->get('knp_gaufrette.filesystem_map')->get('photos');

          $adapter->write("", file_get_contents($tempPath));
         */
    }
}

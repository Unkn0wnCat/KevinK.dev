<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\PhotoAlbum;
use App\Entity\PhotoGallery;
use App\Form\PhotoUploaderType;
use App\Repository\PhotoGalleryRepository;
use App\Repository\PhotoRepository;
use App\Service\PhotoUploader;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotosController
 * @package App\Controller
 * @Route("/photos")
 */
class PhotosController extends AbstractController
{
    private $photosPerPage = 20;

    /**
     * @Route("/", name="photos")
     */
    public function index(PhotoGalleryRepository $galleryRepository)
    {
        return $this->render('photos/index.html.twig', [
            'controller_name' => 'PhotosController',
            'module' => 'photos',
            'galleries' => $galleryRepository->findAll()
        ]);
    }

    /**
     * @Route("/p/{gallery}/{album}/{photo}", name="photoView")
     * @param PhotoGallery $gallery
     * @param PhotoAlbum $album
     * @param Photo $photo
     * @return Response
     */
    public function viewPhoto(PhotoGallery $gallery, PhotoAlbum $album, Photo $photo)
    {
        return $this->render('photos/viewPhoto.html.twig', [
            'module' => 'photos',
            'gallery' => $gallery,
            'album' => $album,
            'photo' => $photo
        ]);
    }

    /**
     * @Route("/g/{gallery}", name="photoViewGallery")
     * @param PhotoGallery $gallery
     * @return Response
     */
    public function viewGallery(PhotoGallery $gallery)
    {
        /*$allPhotos = $gallery->getPhotos()->toArray();

        $photos = array_slice($allPhotos, ($page - 1) * $this->photosPerPage, $this->photosPerPage);*/

        return $this->render('photos/viewGallery.html.twig', [
            'module' => 'photos',
            'albums' => $gallery->getAlbums(),
            'gallery' => $gallery
        ]);
    }

    /**
     * @Route("/a/{gallery}/{album}", name="photoViewAlbum", defaults={"page"=1})
     * @Route("/a/{gallery}/{album}/{page}", name="photoViewAlbumPaged")
     * @param PhotoGallery $gallery
     * @param PhotoAlbum $album
     * @param int $page
     * @return Response
     */
    public function viewAlbum(PhotoGallery $gallery, PhotoAlbum $album, int $page)
    {
        $allPhotos = $album->getPhotos()->toArray();
        $this->sortImageArrayByDatetime($allPhotos);

        $photos = array_slice($allPhotos, ($page - 1) * $this->photosPerPage, $this->photosPerPage);

        return $this->render('photos/viewAlbum.html.twig', [
            'module' => 'photos',
            'gallery' => $gallery,
            'album' => $album,
            'photos' => $photos,
            'page' => $page,
            'maxPage' => ceil(count($allPhotos) / $this->photosPerPage)
        ]);
    }


    /**
     * @Route("/files/p/{photo}", name="photoFileByID", defaults={"width"=-1})
     * @Route("/files/p/{photo}/{width}", name="photoFileByIDScaled")
     * @param Photo $photo
     * @param FilesystemMap $filesystemMap
     * @param int $width
     * @return Response
     */
    public function loadPhoto(Photo $photo, FilesystemMap $filesystemMap, int $width) {
        $filesystem = $filesystemMap->get("photos");

        if($filesystem->has($photo->getSrc())) {

            $file = $filesystem->get($photo->getSrc())->getContent();
            $mime = $filesystem->mimeType($photo->getSrc());

            if($width > 0) {
                if($filesystem->has("scaledCache/".$width."/".$photo->getSrc())) {
                    $file = $filesystem->get("scaledCache/".$width."/".$photo->getSrc())->getContent();
                } else {
                    $image = imagecreatefromstring($file);
                    $scaledImage = imagescale($image, $width, -1, IMG_BICUBIC_FIXED);
                    ob_start();
                    imagejpeg($scaledImage);
                    $file = ob_get_contents();
                    ob_end_clean();
                    imagedestroy($image);
                    imagedestroy($scaledImage);
                    $mime = "image/jpeg";

                    $filesystem->write("scaledCache/".$width."/".$photo->getSrc(), $file);
                }
            }

            $response = new Response();
            $response->setContent($file);
            $response->headers->set("Content-Type", $mime);
            $response->headers->set("Content-Disposition", "inline; filename=\"kevink.dev-".$photo->getId().($width > 0 ? "-".$width."px" : "").".".explode("/", $mime)[1]."\"");

            return $response;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param Photo[] $photoArray
     * @return bool
     */
    function sortImageArrayByDatetime(&$photoArray) {
        return usort($photoArray, function($a, $b) {
            /** @var Photo $a */
            $datetimeA = $a->getCaptureTimestamp();
            /** @var Photo $b */
            $datetimeB = $b->getCaptureTimestamp();

            $interval = $datetimeA->diff($datetimeB);

            if($interval->s == 0) {
                return 0;
            } elseif($interval->s > 0) {
                return 1;
            } else {
                return -1;
            }
        });
    }

    /**
     * @Route("/upload", name="photoUpload", defaults={"galleryID"=-1, "albumID"=-1})
     * @Route("/upload/{galleryID}/{albumID}", name="photoUploadToAlbum")
     * @param int $galleryID
     * @param int $albumID
     * @return Response
     */
    public function uploadUI(int $galleryID, int $albumID) {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('photos/upload.html.twig', [
            'module' => 'photos',
            "galleryID" => $galleryID,
            "albumID" => $albumID
        ]);
    }

    /**
     * @Route("/upload/process", name="photoDoUpload", defaults={"galleryID"=-1, "albumID"=-1})
     * @Route("/upload/process/{galleryID}/{albumID}", name="photoDoUploadToAlbum")
     * @param PhotoUploader $uploaderService
     * @param Request $request
     * @param FilesystemMap $filesystemMap
     * @param int $galleryID
     * @param int $albumID
     * @return Response
     */
    public function upload(PhotoUploader $uploaderService, Request $request, FilesystemMap $filesystemMap, int $galleryID, int $albumID) {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        /*$form = $this->createForm(PhotoUploaderType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {*/
            //$files = $form->get("images")->getData();
            $file = $request->files->get("photo");

            $adapter = $filesystemMap->get('photos');

            $uploaderResponse = $uploaderService->uploadFile($file, $adapter);

            $uploadedFiles = $uploaderResponse['saved'];

            $entityManager = $this->getDoctrine()->getManager();

            if($albumID != -1) {
                $albumRepo = $entityManager->getRepository("App\\Entity\\PhotoAlbum");

                /** @var PhotoAlbum $album */
                $album = $albumRepo->find($albumID);
            }

            /*if($galleryID != -1) {
                $galleryRepo = $entityManager->getRepository("App\\Entity\\PhotoGallery");

                /** @var PhotoGallery $gallery */
                /*$gallery = $galleryRepo->find($galleryID);
            }*/

            $count = 0;

            foreach ($uploadedFiles as $filename => $metadata) {
                $photo = new Photo($filename, $metadata['fileTime'], $metadata['width'], $metadata['height']);

                /*if(isset($gallery) && $gallery) {
                    $gallery->addPhoto($photo);
                    $entityManager->persist($gallery);
                }*/

                if(isset($album) && $album) {
                    $album->addPhoto($photo);
                    $entityManager->persist($album);
                }

                $entityManager->persist($photo);

                $count++;

                if($count % 10) {
                    $entityManager->flush();
                }
            }

            $entityManager->flush();

            return new JsonResponse(array("success"=>true));
        /*} else {
            return $this->render('photos/upload.html.twig', [
                'module' => 'photos',
                'form' => $form->createView()
            ]);
        }*/

        //$uploaderService->uploadFiles();

        /*
          $adapter = $container->get('knp_gaufrette.filesystem_map')->get('photos');

          $adapter->write("", file_get_contents($tempPath));
         */
    }
}

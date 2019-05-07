<?php


namespace App\NoPreController;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StartPageController extends AbstractController
{

    /**
     * StartPage redirect.
     * @Route("/", name="startpage")
     * @param Request $request
     */
    function index(Request $request) {


        $defaultLocale = $this->getBrowserLanguage([
            "en", "de"
        ], "en");


        return $this->redirectToRoute("index", [
            "_locale" => $request->cookies->get("lang") ?: $defaultLocale
        ]);
    }

    /**
     * @param array $available
     * @param string $default
     * @return bool|string
     */
    private function getBrowserLanguage($available = [], $default = 'en' ) {
        if ( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) ) {
            $languages = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
            if ( empty( $available ) ) {
                return $languages[ 0 ];
            }
            foreach ( $languages as $lang ){
                $lang = substr( $lang, 0, 2 );
                if( in_array( $lang, $available ) ) {
                    return $lang;
                }
                if( in_array( explode("-", $lang)[0], $available ) ) {
                    return explode("-", $lang)[0];
                }
            }
        }
        return $default;
    }
}
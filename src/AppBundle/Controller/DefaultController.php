<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Translation\Seccion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if($request->get('changeLocale') !== null) {
            $request->setLocale($request->get('changeLocale'));
        }

        $sectionTranslation = $this->getDoctrine()
            ->getRepository(Seccion::class)
            ->findOneBy(['modulo' => 'MODULO_USUARIOS']);

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'sectionTranslation' => $sectionTranslation !== null ? $sectionTranslation->translate($request->getLocale()) : null
        ]);
    }
}

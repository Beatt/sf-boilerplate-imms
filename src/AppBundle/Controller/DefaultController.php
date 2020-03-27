<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Traductor;
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

        /** @var Traductor $translate */
        $translate = $this->getDoctrine()
            ->getRepository(Traductor::class)
            ->getTextsByLocale($request->getLocale());

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'translate' => $translate !== null ? $translate->getTraductorDTO() : null
        ]);
    }
}

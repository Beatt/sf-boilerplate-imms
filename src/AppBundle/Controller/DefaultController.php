<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Translation\CategoryTranslation;
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

        $categoryTranslation = $this->getDoctrine()
            ->getRepository(CategoryTranslation::class)
            ->findBy(['locale' => $request->getLocale()]);

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'categoryTranslation' => $categoryTranslation
        ]);
    }
}

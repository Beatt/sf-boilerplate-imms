<?php


namespace AppBundle\Controller\Fofoe;

use AppBundle\Repository\ReferenciaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/fofoe")
 */
class ReferenciaController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/inicio", methods={"GET"}, name="fofoe/inicio")
     *
     */
    public function indexAction(Request $request)
    {
        $repository = new ReferenciaRepository($this->getDoctrine()->getManager());
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $referencias = $repository->paginate($perPage, $page, $request->query->all());
        $years = $repository->getYears();
        return $this->render('fofoe/referencia/index.html.twig', [
            'referencias' => $referencias['data'], 'years' => $years,
            'meta' => ['total' => $referencias['total'], 'perPage' => $perPage, 'page' => $page]
        ]);
    }

    /**
     * @Route("/api/pago", methods={"GET"}, name="fofoe.pago.index.api")
     */
    public function indexApiAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $repository = new ReferenciaRepository($this->getDoctrine()->getManager());
        $referencias = $repository->paginate($perPage, $page, $request->query->all());
        return $this->jsonResponse([
            'object' => $referencias['data'],
            'meta' => ['total' => $referencias['total'], 'perPage' => $perPage, 'page' => $page]
        ]);
    }
}
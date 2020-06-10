<?php


namespace AppBundle\Controller\Fofoe;

use AppBundle\Entity\Pago;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class PagoController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/fofoe/pago", methods={"GET"}, name="fofoe.pago.index")
     *
     */
    public function indexAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $pagos = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->paginate($perPage, $page, $request->query->all());
        return $this->render('fofoe/pago/index.html.twig', [
            'pagos' => $this->get('serializer')->normalize(
                $pagos['data'],
                'json',
                [
                    'attributes' => [
                        'id',
                    ]
                ]
            ),
            'meta' => ['total' => $pagos['total'], 'perPage' => $perPage, 'page' => $page]
        ]);

    }

    /**
     * @Route("/fofoe/api/pago", methods={"GET"}, name="fofoe.pago.index.api")
     * @Security("has_role('ROLE_CONSULTAR_SOLICITUDES')")
     */
    public function indexApiAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $pagos = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->paginate($perPage, $page, $request->query->all());
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize(
                $pagos['data'],
                'json',
                [
                    'attributes' => [
                        'id',
                    ]
                ]
            ),
            'meta' => ['total' => $pagos['total'], 'perPage' => $perPage, 'page' => $page]
        ]);
    }
}
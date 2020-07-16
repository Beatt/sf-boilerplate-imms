<?php


namespace AppBundle\Controller\Fofoe;

use AppBundle\Entity\Pago;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/fofoe")
 */
class PagoController extends \AppBundle\Controller\DIEControllerController
{

    /**
     * @Route("/inicio", methods={"GET"}, name="fofoe/inicio")
     *
     */
    public function indexAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $pagos = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->paginate($perPage, $page, $request->query->all());
        $years = $this->getYears();
        return $this->render('fofoe/pago/index.html.twig', [
            'pagos' => $this->get('serializer')->normalize(
                $pagos['data'],
                'json',
                [
                    'attributes' => [
                        'id', 'referenciaBancaria', 'validado', 'requiereFactura', 'fechaPagoFormatted',
                        'factura' => ['id', 'folio'], 'solicitud' => ['id', 'noSolicitud', 'estatus', 'tipoPago',
                            'delegacion' => ['id', 'nombre'],
                            'institucion' => ['id', 'nombre']]
                    ]
                ]
            ),
            'years' => $years,
            'meta' => ['total' => $pagos['total'], 'perPage' => $perPage, 'page' => $page]
        ]);

    }

    private function getYears()
    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'select extract(YEAR from fecha_pago) as year from pago group by 1 order by 1 desc;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * @Route("/api/pago", methods={"GET"}, name="fofoe.pago.index.api")
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
                        'id', 'referenciaBancaria', 'validado', 'requiereFactura', 'fechaPagoFormatted',
                        'factura' => ['id', 'folio'], 'solicitud' => ['id', 'noSolicitud', 'estatus', 'tipoPago',
                            'delegacion' => ['id', 'nombre'],
                            'institucion' => ['id', 'nombre']]
                    ]
                ]
            ),
            'meta' => ['total' => $pagos['total'], 'perPage' => $perPage, 'page' => $page]
        ]);
    }

}
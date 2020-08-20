<?php


namespace AppBundle\Controller\Fofoe;

use AppBundle\Repository\PagoRepositoryInterface;
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

  /**
   * @Route("/referencia/{id}", methods={"GET"}, name="fofoe.referencia.show")
   * @param string $id
   * @param Request $request
   * @param PagoRepositoryInterface $pagoRepository
   * @return Response
   */
    public function showAction(
      $id,
      Request $request,
      PagoRepositoryInterface $pagoRepository)
    {
      $pago = $pagoRepository->findOneBy(['id' => $id]);
      if ( empty($pago) ) throw $this->createNotFoundException(
        'Not found for id ' . $id
      );

      $pagos = $pagoRepository->findBy(['referenciaBancaria' => $pago->getReferenciaBancaria() ]);

      $campos = $pagos[0]->getCamposPagados()['campos'];
      $solicitud = $pagos[0]->getSolicitud();

      return $this->render('fofoe/detalle_referencia/index.html.twig', [
        'pagos' => $this->getNormalizePago($pagos),
        'solicitud' => $this->getNormalizeSolicitud($solicitud),
        'campos' => $this->getNormalizeCampo($campos)
      ]);
    }

  private function getNormalizePago($pago)
  {
    return $this->get('serializer')->normalize(
      $pago,
      'json',
      [
        'attributes' => [
          'id',
          'monto',
          'fechaPago',
          'fechaPagoFormatted',
          'comprobantePago',
          'observaciones',
          'requiereFactura',
          'facturaGenerada',
          'validado',
          'referenciaBancaria',
          'factura' => [
            'id',
            'fechaFacturacion',
            'folio',
            'zip',
            'monto'
          ]
        ]
      ]
    );
  }

  private function getNormalizeCampo($campo) {
    return $this->get('serializer')->normalize(
      $campo,
      'json',
      [
        'attributes' => [
          'id',
          'monto',
          'estatus' => ['nombre'],
          'displayCarrera',
          'unidad' => ['nombre'],
          'displayFechaInicial',
          'displayFechaFinal'
          ]
      ]
    );
  }

  private function getNormalizeSolicitud($solicitud)
  {
    return $this->get('serializer')->normalize(
      $solicitud,
      'json',
      [
        'attributes' => [
          'id',
          'noSolicitud',
          'fecha',
          'tipoPago',
          'estatus',
          'referenciaBancaria',
          'monto',
          'delegacion' => ['nombre'],
          'institucion' => ['id', 'nombre', 'rfc']
        ]
      ]
    );
  }
}
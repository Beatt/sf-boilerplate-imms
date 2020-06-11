<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Repository\CampoClinicoRepository;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class FormatoPagoController extends DIEControllerController
{
    /**
     * @Route("/solicitudes/{id}/generar-formato-de-pago", name="ie#generar_formato_de_pago_unico")
     * @param int $solicitudId
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @return Response
     */
    public function generarFormatoDePagoUnicoAction(
        $solicitudId,
        SolicitudRepositoryInterface $solicitudRepository
    ) {
        $institucionId = $this->getUser()->getInstitucion()->getId();

        $solicitud = $solicitudRepository->find($solicitudId);

        $institucion = $solicitud ? $solicitud->getInstitucion() : null;
        $institucion = $institucion && $institucion->getId() == $institucionId ?
            $institucion : null;

        $solicitud = $institucion ? $solicitud : null;

        $esPagoUnico = $solicitud && $solicitud->getTipoPago() == Solicitud::TIPO_PAGO_UNICO;
        $campos = $esPagoUnico
            ? $solicitud->getCamposClinicos() : null;

        return $this->render('ie/formato/referencia_pago.html.twig',
            ['institucion' => $this->getNormalizeInstitucion($institucion),
                'solicitud' => $this->getNormalizeSolicitud($solicitud),
                'campos' => $this->getNormalizeCampos($campos),
                'esPagoUnico' => $esPagoUnico]);
    }

    /**
     * @Route("/solicitudes/{id}/camposClinicos/{campoId}/generar-formato-de-pago", name="ie#generar_formato_de_pago_multiple")
     * @param int $id
     * @param int $campoId
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepository $campoClinicoRepository
     * @return Response
     */
    public function generarFormatoDePagoMultipleAction(
        $id,
        $campoId,
        SolicitudRepositoryInterface $solicitudRepository,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepository $campoClinicoRepository
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        $institucion = $institucionRepository->find($institucion->getId());

        $campo = $campoClinicoRepository->getAllCamposClinicosByRequest($campoId, 0, true);
        $solicitud = $solicitudRepository->find($id);

        return $this->render('ie/formato/referencia_pago.html.twig',
            ['institucion' => $this->getNormalizeInstitucion($institucion),
                'solicitud' => $this->getNormalizeSolicitud($solicitud),
                'campos' => $this->getNormalizeCampos($campo)]);
    }

    private function getNormalizeInstitucion($institucion)
    {
        return $this->get('serializer')->normalize($institucion, 'json',
            ['attributes' => [
                'id',
                'nombre',
                'rfc'
            ]]);
    }

    private function getNormalizeSolicitud($solicitud)
    {
        return $this->get('serializer')->normalize($solicitud, 'json',
            ['attributes' => [
                'id',
                'noSolicitud',
                'monto',
                'tipoPago'
            ]]);
    }

    private function getNormalizeCampos($campos)
    {
        return $this->get('serializer')->normalize($campos, 'json',
            ['attributes' => [
                'fechaInicial',
                'fechaFinal',
                'lugaresAutorizados',
                'referenciaBancaria',
                'monto',
                'estatus' => ['id', 'nombre'],
                'unidad' => ['nombre'],
                'nombreCicloAcademico',
                'displayCarrera'
            ]]);
    }
}

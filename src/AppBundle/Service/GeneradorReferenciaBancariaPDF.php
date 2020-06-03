<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;
use Knp\Snappy\Pdf;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twig\Environment;

class GeneradorReferenciaBancariaPDF implements GeneradorReferenciaBancariaPDFInterface
{
    private $pdf;

    /**
     * @var Environment
     */
    private $templating;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Pdf $pdf, Environment $templating)
    {
        $this->pdf = $pdf;
        $this->templating = $templating;

        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function generarPDF(Solicitud $solicitud, $directoryOutput)
    {
        $output = $directoryOutput . '/' . $solicitud->getNoSolicitud();
        $institucion = $solicitud->getInstitucion();
        $campos = $solicitud->getCamposClinicos();
        $esPagoUnico = $solicitud->getTipoPago() == Solicitud::TIPO_PAGO_UNICO;

        if ($esPagoUnico) {
          $output = $output . '.pdf';
          $this->generarPDFPago($solicitud, $institucion, $campos, $esPagoUnico, $output);
        } else {
          $i=1;
          foreach ($campos as $campo) {
            $output = $output . '-' . strval($i++) . '.pdf';
            $this->generarPDFPago($solicitud, $institucion, [$campo], $esPagoUnico, $output);
          }
        }

      $finder = new Finder();
      $finder->files()->in($directoryOutput);

        return $finder;
    }

    private function generarPDFPago($solicitud, $institucion, $campos, $esPagoUnico, $output) {
      $this->pdf->generateFromHtml(
        $this->templating->render(
          'institucion_educativa/formatos/ReferenciaPago.html.twig',
          ['institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud),
            'campos' => $this->getNormalizeCampos($campos),
            'esPagoUnico' => $esPagoUnico]
        ),
        $output,
        [],
        true
      );
    }

    private function getNormalizeInstitucion($institucion)
    {
      return $this->serializer->normalize($institucion, 'json',
        ['attributes' => [
          'id',
          'nombre',
          'rfc'
        ]]);
    }

    private function getNormalizeSolicitud($solicitud)
    {
      return $this->serializer->normalize($solicitud, 'json',
        ['attributes' => [
          'id',
          'noSolicitud',
          'monto',
          'tipoPago'
        ]]);
    }

    private function getNormalizeCampos($campos)
    {
      return $this->serializer->normalize($campos, 'json',
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

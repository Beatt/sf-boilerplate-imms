<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;
use Knp\Snappy\Pdf;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Environment;

class GeneradorReferenciaBancariaPDF implements GeneradorReferenciaBancariaPDFInterface
{
    const JSON_FORMAT = 'json';

    private $pdf;

    private $templating;

    private $normalizer;

    public function __construct(
        Pdf $pdf,
        Environment $templating,
        NormalizerInterface $normalizer
    ) {
        $this->pdf = $pdf;
        $this->templating = $templating;
        $this->normalizer = $normalizer;
    }

    public function generarPDF(Solicitud $solicitud, $directoryOutput)
    {
        $baseName = $directoryOutput . '/' . $solicitud->getNoSolicitud();
        $institucion = $solicitud->getInstitucion();
        $campos = $solicitud->getCamposClinicos();
        $esPagoUnico = $solicitud->getTipoPago() == Solicitud::TIPO_PAGO_UNICO;

        if ($esPagoUnico) {
            $output = $baseName . '.pdf';
            $this->generarPDFPago($solicitud, $institucion, $campos, $esPagoUnico,
              $solicitud->getReferenciaBancaria(), $output);
        } else {
            $i = 1;
            foreach ($campos as $campo) {
                $output = $baseName . '-' . strval($i++) . '.pdf';
                $this->generarPDFPago($solicitud, $institucion, [$campo], $esPagoUnico,
                  $campo->getReferenciaBancaria(), $output);
            }
        }

        $finder = new Finder();
        $finder->files()->in($directoryOutput);

        return $finder;
    }

    private function generarPDFPago($solicitud, $institucion, $campos,
                                    $esPagoUnico, $referencia, $output)
    {
        $this->pdf->generateFromHtml(
            $this->templating->render(
                'ie/formato/solicitud/referencia_pago.html.twig',
                ['institucion' => $this->getNormalizeInstitucion($institucion),
                    'solicitud' => $this->getNormalizeSolicitud($solicitud),
                    'campos' => $this->getNormalizeCampos($campos),
                    'esPagoUnico' => $esPagoUnico,
                   'referencia' => $referencia]
            ),
            $output,
          ['page-size' => 'Letter','encoding' => 'utf-8'],
          false
        );
    }

    private function getNormalizeInstitucion($institucion)
    {
        return $this->normalizer->normalize(
            $institucion,
            self::JSON_FORMAT,
            ['attributes' => [
                'id',
                'nombre',
                'rfc'
            ]]);
    }

    private function getNormalizeSolicitud($solicitud)
    {
        return $this->normalizer->normalize(
            $solicitud,
            self::JSON_FORMAT,
            ['attributes' => [
                'id',
                'noSolicitud',
                'monto',
                'tipoPago'
            ]]);
    }

    private function getNormalizeCampos($campos)
    {
        return $this->normalizer->normalize(
            $campos,
            self::JSON_FORMAT,
            ['attributes' => [
                'fechaInicialFormatted',
                'fechaFinalFormatted',
                'lugaresAutorizados',
                'referenciaBancaria',
                'weeks',
                'monto',
                'estatus' => ['id', 'nombre'],
                'unidad' => ['nombre'],
                'nombreCicloAcademico',
                'displayCarrera'
            ]]);
    }
}

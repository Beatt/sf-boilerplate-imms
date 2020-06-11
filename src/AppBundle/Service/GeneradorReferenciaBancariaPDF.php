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
        $output = $directoryOutput . '/' . $solicitud->getNoSolicitud();
        $institucion = $solicitud->getInstitucion();
        $campos = $solicitud->getCamposClinicos();
        $esPagoUnico = $solicitud->getTipoPago() == Solicitud::TIPO_PAGO_UNICO;

        if ($esPagoUnico) {
            $output = $output . '.pdf';
            $this->generarPDFPago($solicitud, $institucion, $campos, $esPagoUnico, $output);
        } else {
            $i = 1;
            foreach ($campos as $campo) {
                $output = $output . '-' . strval($i++) . '.pdf';
                $this->generarPDFPago($solicitud, $institucion, [$campo], $esPagoUnico, $output);
            }
        }

        $finder = new Finder();
        $finder->files()->in($directoryOutput);

        return $finder;
    }

    private function generarPDFPago($solicitud, $institucion, $campos, $esPagoUnico, $output)
    {
        $this->pdf->generateFromHtml(
            $this->templating->render(
                'ie/formato/solicitud/referencia_pago.html.twig',
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

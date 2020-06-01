<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;
use Knp\Snappy\Pdf;
use Symfony\Component\Finder\Finder;

class GeneradorReferenciaBancariaPDF implements GeneradorReferenciaBancariaPDFInterface
{
    private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    public function generarPDF(Solicitud $solicitud, $directoryOutput)
    {
        $output = $directoryOutput . '/file.pdf';

        $this->pdf->generate(
            'http://www.google.fr',
            $output,
            [],
            true
        );

        $finder = new Finder();
        $finder->files()->in($directoryOutput);

        return $finder;
    }
}

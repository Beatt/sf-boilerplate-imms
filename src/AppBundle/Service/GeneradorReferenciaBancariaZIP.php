<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class GeneradorReferenciaBancariaZIP implements GeneradorReferenciaBancariaZIPInterface
{
    private $entityManager;

    private $generadorReferenciaBancariaPDF;

    private $directoryOutput;

    public function __construct(
        EntityManagerInterface $entityManager,
        GeneradorReferenciaBancariaPDFInterface $generadorReferenciaBancariaPDF,
        $directoryOutput
    ) {
        $this->entityManager = $entityManager;
        $this->generadorReferenciaBancariaPDF = $generadorReferenciaBancariaPDF;
        $this->directoryOutput = $directoryOutput;
    }

    public function generarZipResponse(Solicitud $solicitud)
    {
        $files = $this->generadorReferenciaBancariaPDF->generarPDF(
            $solicitud,
            $this->directoryOutput
        );

        $zip = new ZipArchive();
        $zipName = 'Documents.zip';
        $zip->open($zipName,  ZipArchive::CREATE);
        foreach($files as $file) $zip->addFromString(basename($file), file_get_contents($file));
        $zip->close();

        $response = new Response(file_get_contents($zipName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
        $response->headers->set('Content-length', filesize($zipName));

        $filesystem = new Filesystem();
        $filesystem->remove($this->directoryOutput);
        unlink($zipName);

        return $response;
    }
}

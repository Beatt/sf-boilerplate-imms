<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class GeneradorReferenciaBancariaZIP implements GeneradorReferenciaBancariaZIPInterface
{
    const ZIP_NAME = 'ReferenciasBancarias.zip';

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

        $this->createZip($files);
        $response = $this->getZipResponse();

        $this->removeFiles();

        return $response;
    }

    /**
     * @param $files
     * @return string
     */
    protected function createZip($files)
    {
        $zip = new ZipArchive();
        $zip->open(self::ZIP_NAME, ZipArchive::CREATE);
        $this->addFilesToZip($files, $zip);
        $zip->close();
    }

    /**
     * @return Response
     */
    protected function getZipResponse()
    {
        $response = new Response(file_get_contents(self::ZIP_NAME));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . self::ZIP_NAME . '"');
        $response->headers->set('Content-length', filesize(self::ZIP_NAME));
        return $response;
    }

    protected function removeFiles()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->directoryOutput);
        unlink(self::ZIP_NAME);
    }

    /**
     * @param $files
     * @param ZipArchive $zip
     */
    protected function addFilesToZip($files, ZipArchive $zip)
    {
        foreach ($files as $file) $zip->addFromString(basename($file), file_get_contents($file));
    }
}

<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;
use AppBundle\Event\BankReferencesCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class GeneradorReferenciaBancariaZIP implements GeneradorReferenciaBancariaZIPInterface
{
    private $entityManager;

    private $generadorReferenciaBancariaPDF;

    private $dispatcher;

    private $directoryOutput;

    private $zipReferenciasBancariasDir;

    public function __construct(
        EntityManagerInterface $entityManager,
        GeneradorReferenciaBancariaPDFInterface $generadorReferenciaBancariaPDF,
        EventDispatcherInterface $dispatcher,
        $directoryOutput,
        $zipReferenciasBancariasDir
    ) {
        $this->entityManager = $entityManager;
        $this->generadorReferenciaBancariaPDF = $generadorReferenciaBancariaPDF;
        $this->directoryOutput = $directoryOutput;
        $this->dispatcher = $dispatcher;
        $this->zipReferenciasBancariasDir = $zipReferenciasBancariasDir;
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

        $this->dispatcher->dispatch(
            BankReferencesCreatedEvent::NAME,
            new BankReferencesCreatedEvent($solicitud)
        );

        return $response;
    }

    /**
     * @param $files
     * @return string
     */
    private function createZip($files)
    {
        $zip = new ZipArchive();
        $zip->open($this->zipReferenciasBancariasDir, ZipArchive::CREATE);
        $this->addFilesToZip($files, $zip);
        $zip->close();
    }

    /**
     * @return Response
     */
    private function getZipResponse()
    {
        $response = new Response(file_get_contents($this->zipReferenciasBancariasDir));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="ReferenciasBancaria.zip"');
        $response->headers->set('Content-length', filesize($this->zipReferenciasBancariasDir));
        return $response;
    }

    private function removeFiles()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->directoryOutput);
        unlink($this->zipReferenciasBancariasDir);
    }

    /**
     * @param $files
     * @param ZipArchive $zip
     */
    private function addFilesToZip($files, ZipArchive $zip)
    {
        foreach ($files as $file) $zip->addFromString(basename($file), file_get_contents($file));
    }
}

<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class GeneradorFormatosFofoeZIP implements GeneradorFormatosFofoeZIPInterface
{
    private $campoClinicoRepository;

    private $generadorFormatoFofoe;

    private $formatoFofoeDir;

    private $zipFormatosFofoeDir;

    public function __construct(
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        GeneradorFormatoFofoeInterface $generadorFormatoFofoe,
        $formatoFofoeDir,
        $zipFormatosFofoeDir
    ) {
        $this->campoClinicoRepository = $campoClinicoRepository;
        $this->generadorFormatoFofoe = $generadorFormatoFofoe;
        $this->formatoFofoeDir = $formatoFofoeDir;
        $this->zipFormatosFofoeDir = $zipFormatosFofoeDir;
    }

    public function generarPDF(Solicitud $solicitud)
    {
        $camposClinicosAutorizados = $this->getCamposClinicosAutorizados($solicitud);

        $zip = $this->createZip();
        foreach($camposClinicosAutorizados as $campoClinico) {
            $file = $this->generadorFormatoFofoe->responsePdf(
                $this->formatoFofoeDir,
                $campoClinico
            );
            $zip->addFromString(basename($file), file_get_contents($file));
        }
        $zip->close();

        $response = $this->getZipResponse();

        $this->removeFiles();

        return $response;
    }

    /**
     * @return string
     */
    private function createZip()
    {
        $zip = new ZipArchive();
        $zip->open($this->zipFormatosFofoeDir, ZipArchive::CREATE);
        return $zip;
    }

    /**
     * @return Response
     */
    private function getZipResponse()
    {
        $response = new Response(file_get_contents($this->zipFormatosFofoeDir));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="FormatosFOFOE.zip"');
        $response->headers->set('Content-length', filesize($this->zipFormatosFofoeDir));
        return $response;
    }

    private function removeFiles()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->formatoFofoeDir);
        unlink($this->zipFormatosFofoeDir);
    }

    /**
     * @param Solicitud $solicitud
     * @return mixed
     */
    private function getCamposClinicosAutorizados(Solicitud $solicitud)
    {
        return $this->campoClinicoRepository
            ->createQueryBuilder('campoClinico')
            ->where('campoClinico.solicitud = :id AND campoClinico.lugaresAutorizados != 0')
            ->setParameter('id', $solicitud->getId())
            ->getQuery()
            ->getResult();
    }
}

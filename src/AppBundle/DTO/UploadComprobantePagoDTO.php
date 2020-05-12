<?php

namespace AppBundle\DTO;

use AppBundle\Entity\CampoClinico;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadComprobantePagoDTO
{
    /**
     * @var UploadedFile
     * @Assert\File(
     *     maxSize="1000000",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     * )
     */
    private $file;

    /**
     * @var CampoClinico
     */
    private $campoClinico;

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return CampoClinico
     */
    public function getCampoClinico()
    {
        return $this->campoClinico;
    }

    /**
     * @param CampoClinico $campoClinico
     */
    public function setCampoClinico(CampoClinico $campoClinico)
    {
        $this->campoClinico = $campoClinico;
    }
}

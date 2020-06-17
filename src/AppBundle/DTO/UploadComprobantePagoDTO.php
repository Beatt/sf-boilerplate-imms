<?php

namespace AppBundle\DTO;

use AppBundle\Entity\Pago;
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
     * @var Pago
     */
    private $pago;

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
     * @return Pago
     */
    public function getPago()
    {
        return $this->pago;
    }

    /**
     * @param Pago $pago
     */
    public function setPago(Pago $pago)
    {
        $this->pago = $pago;
    }
}

<?php

namespace AppBundle\Service;

use AppBundle\Entity\ComprobantePagoInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderComprobantePagoInterface
{
    public function update(ComprobantePagoInterface $campoClinico, UploadedFile $file);
}

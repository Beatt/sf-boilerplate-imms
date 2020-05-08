<?php

namespace AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderComprobantePagoInterface
{
    public function update(CampoClinico $campoClinico, UploadedFile $file);
}

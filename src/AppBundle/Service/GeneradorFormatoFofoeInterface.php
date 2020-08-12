<?php

namespace AppBundle\Service;

use AppBundle\Entity\CampoClinico;

interface GeneradorFormatoFofoeInterface
{
    public function responsePdf($path, CampoClinico $campoClinico, $overwrite = false);

    public function getFileName(CampoClinico $campoClinico);
}

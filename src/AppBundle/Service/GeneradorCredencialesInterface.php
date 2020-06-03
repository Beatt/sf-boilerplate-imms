<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;

interface GeneradorCredencialesInterface
{
    public function responsePdf($path, CampoClinico $campoClinico, $overwrite = false);
}
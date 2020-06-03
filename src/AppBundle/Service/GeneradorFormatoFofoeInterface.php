<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Usuario;

interface GeneradorFormatoFofoeInterface
{

    public function responsePdf($path, CampoClinico $campoClinico, Usuario $came = null, $overwrite = false);

}
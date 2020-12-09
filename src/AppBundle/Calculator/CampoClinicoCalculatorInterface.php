<?php

namespace AppBundle\Calculator;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\CampoClinico;

interface CampoClinicoCalculatorInterface
{
    public function getMontoAPagar(CampoClinico $campo, Solicitud $solicitud);
}

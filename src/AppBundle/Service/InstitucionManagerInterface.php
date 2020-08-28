<?php

namespace AppBundle\Service;

use AppBundle\Entity\Institucion;

interface InstitucionManagerInterface
{
    public function update(Institucion $institucion);
}

<?php

namespace AppBundle\Service;

use AppBundle\Entity\Institucion;
use Doctrine\ORM\EntityManagerInterface;

interface InstitucionManagerInterface
{
    public function Create(Institucion $institucion);
}

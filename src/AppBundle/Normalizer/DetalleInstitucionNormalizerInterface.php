<?php

namespace AppBundle\Normalizer;

use AppBundle\Entity\Institucion;

interface DetalleInstitucionNormalizerInterface
{
    public function normalizeConvenios(array $camposClinicos);
    public function normalizeInstitucion(Institucion $institucion);
}

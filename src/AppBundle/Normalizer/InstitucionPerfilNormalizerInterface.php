<?php

namespace AppBundle\Normalizer;

use AppBundle\Entity\Institucion;

interface InstitucionPerfilNormalizerInterface
{
    public function normalizeCamposClinicos(array $camposClinicos);
    public function normalizeInstitucion(Institucion $institucion);
}

<?php

namespace AppBundle\Normalizer;

use Doctrine\Common\Collections\Collection;

interface FormaPagoNormalizerInterface
{
    public function normalizeCamposClinicos(Collection $camposClinicos);
}

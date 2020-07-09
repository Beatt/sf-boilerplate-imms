<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

final class Documents
{
    private $oficioMontos;

    public function __construct(OficioMontos $oficioMontos)
    {
        $this->oficioMontos = $oficioMontos;
    }

    /**
     * @return OficioMontos
     */
    public function getOficioMontos()
    {
        return $this->oficioMontos;
    }
}

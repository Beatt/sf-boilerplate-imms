<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

final class Documents
{
    private $oficioMontos;

    private $comprobantesPago;

    private $facturas;

    public function __construct(
        OficioMontos $oficioMontos,
        array $comprobantesPago,
        array $facturas
    ) {
        $this->oficioMontos = $oficioMontos;
        $this->comprobantesPago = $comprobantesPago;
        $this->facturas = $facturas;
    }

    /**
     * @return OficioMontos
     */
    public function getOficioMontos()
    {
        return $this->oficioMontos;
    }

    /**
     * @return array
     */
    public function getComprobantesPago()
    {
        return $this->comprobantesPago;
    }

    /**
     * @return array
     */
    public function getFacturas()
    {
        return $this->facturas;
    }
}

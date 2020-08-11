<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

final class Documents
{
    private $oficioMontos;

    private $comprobantesPago;

    private $facturas;

    private $formatosFofoe;

    public function __construct(
        OficioMontos $oficioMontos,
        array $comprobantesPago,
        array $facturas,
        FormatosFofoe $formatosFofoe = null
    ) {
        $this->oficioMontos = $oficioMontos;
        $this->comprobantesPago = $comprobantesPago;
        $this->facturas = $facturas;
        $this->formatosFofoe = $formatosFofoe;
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

    /**
     * @return FormatosFofoe
     */
    public function getFormatosFofoe()
    {
        return $this->formatosFofoe;
    }
}

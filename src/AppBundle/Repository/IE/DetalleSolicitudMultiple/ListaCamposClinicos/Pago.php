<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\ListaCamposClinicos;

use AppBundle\Normalizer\FacturaFileInterface;

final class Pago implements FacturaFileInterface
{
    private $id;

    private $urlArchivo;

    private $requiereFactura;

    public function __construct($id, $factura, $requiereFactura)
    {
        $this->id = $id;
        $this->urlArchivo = $factura;
        $this->requiereFactura = $requiereFactura;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUrlArchivo()
    {
        return $this->urlArchivo;
    }

    /**
     * @return bool
     */
    public function getRequiereFactura()
    {
        return $this->requiereFactura;
    }
}

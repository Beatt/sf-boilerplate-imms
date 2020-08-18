<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\ListaCamposClinicos;

final class Pago
{
    private $id;

    private $facturaId;

    private $requiereFactura;

    public function __construct($id, $facturaId, $requiereFactura)
    {
        $this->id = $id;
        $this->facturaId = $facturaId;
        $this->requiereFactura = $requiereFactura;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFacturaId()
    {
        return $this->facturaId;
    }

    /**
     * @return bool
     */
    public function getRequiereFactura()
    {
        return $this->requiereFactura;
    }
}

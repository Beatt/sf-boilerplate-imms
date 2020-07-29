<?php

namespace AppBundle\Repository\IE\DetalleSolicitud;

final class UltimoPago
{
    private $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}

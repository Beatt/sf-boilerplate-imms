<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

interface DocumentInterface
{
    /**
     * @return string
     */
    public function getNombre();

    /**
     * @return string
     */
    public function getFecha();

    /**
     * @return string
     */
    public function getDescripcion();

    /**
     * @return string
     */
    public function getUrlArchivo();
}

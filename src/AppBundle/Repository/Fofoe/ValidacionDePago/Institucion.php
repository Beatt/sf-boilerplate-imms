<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Institucion
{
    private $nombre;

    private $delegacion;

    private $id;

    private $rfc;

    public function __construct(
        $id,
        $nombre,
        $delegacion,
        $rfc
    ) {
        $this->nombre = $nombre;
        $this->delegacion = $delegacion;
        $this->id = $id;
        $this->rfc = $rfc;
    }

    /**
     * @return string
     */
    public function getDelegacion()
    {
        return $this->delegacion;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
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
    public function getRfc()
    {
        return $this->rfc;
    }
}

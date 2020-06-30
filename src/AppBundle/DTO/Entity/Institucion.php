<?php

namespace AppBundle\DTO\Entity;

use AppBundle\Entity\Institucion as InstitucionBase;

class Institucion extends InstitucionBase
{
    public function __construct(InstitucionBase $institucion)
    {
        parent::__construct();

        $this->id = $institucion->id;
        $this->nombre = $institucion->nombre;
        $this->rfc = $institucion->rfc;
        $this->direccion = $institucion->direccion;
        $this->correo = $institucion->correo;
        $this->telefono = $institucion->telefono;
        $this->extension = $institucion->extension;
        $this->fax = $institucion->fax;
        $this->sitioWeb = $institucion->sitioWeb;
        $this->cedulaIdentificacion = $institucion->cedulaIdentificacion;
        $this->representante = $institucion->representante;
        $this->confirmacionInformacion = $institucion->confirmacionInformacion;
    }
}

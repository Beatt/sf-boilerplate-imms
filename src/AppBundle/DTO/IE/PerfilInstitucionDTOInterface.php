<?php

namespace AppBundle\DTO\IE;

interface PerfilInstitucionDTOInterface
{
    public function getId();

    public function getNombre();

    public function getRfc();

    public function getDireccion();

    public function getCorreo();

    public function getTelefono();

    public function getFax();

    public function getSitioWeb();

    public function getCedulaIdentificacion();

    public function getRepresentante();
}

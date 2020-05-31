<?php

namespace AppBundle\Service;

class GeneradorReferenciaBancariaZip implements GeneradorReferenciaBancariaZipInterface
{
    public function __construct($urlFormatoReferenciaBancaria)
    {
    }

    public function generarZip()
    {
        // Si es solicitud tipo pago unico, crear un único documento de referencia de pago
        // Si es solicitud tipo pago multiple, iterar a tráves de los campos y crear pdf
    }
}

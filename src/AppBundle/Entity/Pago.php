<?php

namespace AppBundle\Entity;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Pago
 *
 * @ORM\Table(name="pago")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PagoRepository")
 * @Vich\Uploadable
 */
class Pago
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $monto;

     /**
      *
      * @ORM\Column(type="date", nullable=true)
     */
     private $fechaPago;

    /**
     * @var Solicitud
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Solicitud", inversedBy="pago")
     * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
     */
     private $solicitud;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $comprobantePago;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="comprobantes_pagos", fileNameProperty="comprobantePago")
     */
    private $comprobantePagoFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $referenciaBancaria;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $validado;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $requiereFactura;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $observaciones;

    /**
     * @var Factura
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Factura", inversedBy="factura")
     * @ORM\JoinColumn(name="factura_id", referencedColumnName="id")
     */
     private $factura;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $monto
     * @return Pago
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @param Solicitud $solicitud
     * @return Pago
     */
     public function setSolicitud(Solicitud $solicitud = null)
     {
         $this->solicitud = $solicitud;

         return $this;
     }

     /**
      * @return Solicitud
      */
     public function getSolicitud()
     {
         return $this->solicitud;
     }

    /**
     * @param string $comprobantePago
     * @return Pago
     */
    public function setComprobantePago($comprobantePago)
    {
        $this->comprobantePago = $comprobantePago;

        return $this;
    }

    /**
     * @return string
     */
    public function getComprobantePago()
    {
        return $this->comprobantePago;
    }

    /**
     * @param string $referenciaBancaria
     * @return Pago
     */
    public function setReferenciaBancaria($referenciaBancaria)
    {
        $this->referenciaBancaria = $referenciaBancaria;

        return $this;
    }

    /**
     * @return string
     */
    public function getReferenciaBancaria()
    {
        return $this->referenciaBancaria;
    }

    /**
     * @param boolean $validado
     * @return Pago
     */
    public function setValidado($validado)
    {
        $this->validado = $validado;

        return $this;
    }

    /**
     * @return bool
     */
    public function getValidado()
    {
        return $this->validado;
    }

    /**
     * @param string $observaciones
     * @return Pago
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * @return Factura
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * @param Factura $factura
     */
    public function setFactura(Factura $factura)
    {
        $this->factura = $factura;
    }

    /**
     * @return bool
     */
    public function isRequiereFactura()
    {
        return $this->requiereFactura;
    }

    /**
     * @param bool $requiereFactura
     */
    public function setRequiereFactura($requiereFactura)
    {
        $this->requiereFactura = $requiereFactura;
    }

    /**
     * @return File
     */
    public function getComprobantePagoFile()
    {
        return $this->comprobantePagoFile;
    }

    /**
     * @param File $comprobantePagoFile
     */
    public function setComprobantePagoFile($comprobantePagoFile = null)
    {
        $this->comprobantePagoFile = $comprobantePagoFile;

        $this->setFechaPago(Carbon::now());
    }

    /**
     * @param DateTime $fechaPago
     * @return Pago
     */
     public function setFechaPago($fechaPago = null)
     {
         $this->fechaPago = $fechaPago;

         return $this;
     }

     /**
      * @return DateTime
      */
     public function getFechaPago()
     {
         return $this->fechaPago;
     }

    /**
     * @return string
     */
     public function getFechaPagoFormatted()
     {
         if($this->getFechaPago()){
             return $this->getFechaPago()->format('d-m-Y');
         }
         return '';
     }
}

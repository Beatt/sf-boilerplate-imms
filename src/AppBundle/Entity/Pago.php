<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pago
 *
 * @ORM\Table(name="pago")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PagoRepository")
 */
class Pago
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="monto", type="decimal", precision=10, scale=4)
     */
    private $monto;

    /**
     * @var int
     *
     * @ORM\Column(name="solicitud_id", type="integer")
     */
    private $solicitudId;

    /**
     * @var string
     *
     * @ORM\Column(name="comprobante_pago", type="string", length=100)
     */
    private $comprobantePago;

    /**
     * @var string
     *
     * @ORM\Column(name="referencia_bancaria", type="string", length=100)
     */
    private $referenciaBancaria;

    /**
     * @var bool
     *
     * @ORM\Column(name="validado", type="boolean")
     */
    private $validado;

    /**
     * @var string
     *
     * @ORM\Column(name="xml", type="string", length=100)
     */
    private $xml;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf", type="string", length=100)
     */
    private $pdf;

    /**
     * @var bool
     *
     * @ORM\Column(name="factura", type="boolean")
     */
    private $factura;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=100)
     */
    private $observaciones;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set monto
     *
     * @param string $monto
     *
     * @return Pago
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set solicitudId
     *
     * @param integer $solicitudId
     *
     * @return Pago
     */
    public function setSolicitudId($solicitudId)
    {
        $this->solicitudId = $solicitudId;

        return $this;
    }

    /**
     * Get solicitudId
     *
     * @return int
     */
    public function getSolicitudId()
    {
        return $this->solicitudId;
    }

    /**
     * Set comprobantePago
     *
     * @param string $comprobantePago
     *
     * @return Pago
     */
    public function setComprobantePago($comprobantePago)
    {
        $this->comprobantePago = $comprobantePago;

        return $this;
    }

    /**
     * Get comprobantePago
     *
     * @return string
     */
    public function getComprobantePago()
    {
        return $this->comprobantePago;
    }

    /**
     * Set referenciaBancaria
     *
     * @param string $referenciaBancaria
     *
     * @return Pago
     */
    public function setReferenciaBancaria($referenciaBancaria)
    {
        $this->referenciaBancaria = $referenciaBancaria;

        return $this;
    }

    /**
     * Get referenciaBancaria
     *
     * @return string
     */
    public function getReferenciaBancaria()
    {
        return $this->referenciaBancaria;
    }

    /**
     * Set validado
     *
     * @param boolean $validado
     *
     * @return Pago
     */
    public function setValidado($validado)
    {
        $this->validado = $validado;

        return $this;
    }

    /**
     * Get validado
     *
     * @return bool
     */
    public function getValidado()
    {
        return $this->validado;
    }

    /**
     * Set xml
     *
     * @param string $xml
     *
     * @return Pago
     */
    public function setXml($xml)
    {
        $this->xml = $xml;

        return $this;
    }

    /**
     * Get xml
     *
     * @return string
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * Set pdf
     *
     * @param string $pdf
     *
     * @return Pago
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf
     *
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Set factura
     *
     * @param boolean $factura
     *
     * @return Pago
     */
    public function setFactura($factura)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return bool
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Pago
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }
}


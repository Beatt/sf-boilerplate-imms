<?php

namespace AppBundle\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="factura")
 * @ORM\Entity
 */
class Factura
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
     * @ORM\Column(type="string", length=100)
     */
    private $zip;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $fechaFacturacion;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $monto;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $folio;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return DateTime
     */
    public function getFechaFacturacion()
    {
        return $this->fechaFacturacion;
    }

    /**
     * @param DateTime $fechaFacturacion
     */
    public function setFechaFacturacion($fechaFacturacion)
    {
        $this->fechaFacturacion = $fechaFacturacion;
    }

    /**
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @param string $monto
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;
    }

    /**
     * @return string
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * @param string $folio
     */
    public function setFolio($folio)
    {
        $this->folio = $folio;
    }
}

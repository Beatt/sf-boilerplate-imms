<?php

namespace AppBundle\Entity;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="factura")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FacturaRepository")
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
     * @ORM\Column(type="date")
     */
    private $fechaFacturacion;


    /**
     * @ORM\Column(type="float", precision=24, scale=4, nullable=true)
     */
     private $monto;


     /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
     private $folio;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $zip
     * @return Factura
     */
     public function setZip($zip)
     {
         $this->zip = $zip;
 
         return $this;
     }
 
     /**
      * @return string
      */
     public function getZip()
     {
         return $this->zip;
     }

    /**
     * @param DateTime $fechaFacturacion
     * @return Factura
     */
    public function setFechaFacturacion($fechaFacturacion)
    {
        $this->fechaFacturacion = $fechaFacturacion;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFechaFacturacion()
    {
        return $this->fechaFacturacion;
    }

    /**
     * @param float $monto
     * @return Factura
     */
     public function setMonto($monto)
     {
         $this->monto = $monto;
 
         return $this;
     }
 
     /**
      * @return float
      */
     public function getMonto()
     {
         return $this->monto;
     }


     /**
     * @param string $folio
     * @return Factura
     */
     public function setFolio($folio)
     {
         $this->folio = $folio;
 
         return $this;
     }
 
     /**
      * @return string
      */
     public function getFolio()
     {
         return $this->folio;
     }

    
}

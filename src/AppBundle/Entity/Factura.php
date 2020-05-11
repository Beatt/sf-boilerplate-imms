<?php

namespace AppBundle\Entity;

<<<<<<< HEAD
use Carbon\Carbon;
=======

>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="factura")
<<<<<<< HEAD
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FacturaRepository")
=======
 * @ORM\Entity
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
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
<<<<<<< HEAD
    
=======

>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
<<<<<<< HEAD
     private $zip;

     /**
=======
    private $zip;

    /**
     * @var DateTime
     *
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
     * @ORM\Column(type="date")
     */
    private $fechaFacturacion;

<<<<<<< HEAD

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
=======
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
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
     */
    public function getId()
    {
        return $this->id;
    }

    /**
<<<<<<< HEAD
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
=======
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
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
     */
    public function setFechaFacturacion($fechaFacturacion)
    {
        $this->fechaFacturacion = $fechaFacturacion;
<<<<<<< HEAD

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

    
=======
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
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
}

<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Carbon\Carbon;

/**
 * @ORM\Table(name="factura")
 * @ORM\Entity
 * @Vich\Uploadable
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
     private $zip;

    /**
     * @var DateTime
     *
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
     * @var File
     *
     * @Vich\UploadableField(mapping="facturas", fileNameProperty="zip")
     */

    private $zipFile;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $aux;

    /**
     * @var Pago
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Pago", mappedBy="factura")
     */
    private $pago;

    /**
     * @return int
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
     * @return DateTime
     */
    public function getFechaFacturacion()
    {
        return $this->fechaFacturacion;
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

    /**
     * @return string
     */
     public function getFechaFacturacionFormatted()
     {
         if($this->getFechaFacturacion()){
             return $this->getFechaFacturacion()->format('d-m-Y');
         }
         return '';
     }

     /**
     * @return File
     */
    public function getZipFile()
    {
        return $this->zipFile;
    }

    /**
     * @param File $zipFile
     */
    public function setZipFile($zipFile = null)
    {
        $this->zipFile = $zipFile;

    }

    /**
      * @return int
      */
      public function getAux()
      {
          return $this->aux;
      }


      /**
      * @param string $aux
      * @return Factura
      */
      public function setAux($aux)
      {
          $this->aux = $aux;

          return $this;
      }

    /**
     * @return Pago
     */
    public function getPago()
    {
        return $this->pago;
    }
}

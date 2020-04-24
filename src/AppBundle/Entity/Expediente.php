<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="expediente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExpedienteRepository")
 */
class Expediente
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
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    private $urlArchivo;

    /**
     * @var Solictud
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Solicitud")
     * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
     */
    private $solicitud;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", length=100)
     */
    private $fecha;


    public function __construct()
    {
        $this->fecha = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $descripcion
     * @return Expediente
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }


    /**
     * @param \DateTime $fecha
     * @return Delegacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @param string $urlArchivo
     * @return Solicitud
     */
    public function setUrlArchivo($urlArchivo)
    {
        $this->urlArchivo = $urlArchivo;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlArchivo()
    {
        return $this->urlArchivo;
    }

    /**
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}

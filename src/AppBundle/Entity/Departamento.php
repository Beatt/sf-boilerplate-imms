<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="departamento")
 * @ORM\Entity
 */
class Departamento
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
     * @ORM\Column(type="string", length=30)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=12)
     */
    private $claveDepartamental;

    /**
     * @var string
     * @ORM\Column(type="string", length=12)
     */
    private $clavePresupuestal;

    /**
     * @var Unidad
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unidad")
     * @ORM\JoinColumn(name="unidad_id", referencedColumnName="id")
     */
    private $unidad;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $esUnidad;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $anio;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $nombre
     * @return Departamento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param boolean $activo
     * @return Departamento
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * @param string $claveDepartamental
     * @return Departamento
     */
    public function setClaveDepartamental($claveDepartamental)
    {
        $this->claveDepartamental = $claveDepartamental;

        return $this;
    }

    /**
     * @return string
     */
    public function getClaveDepartamental()
    {
        return $this->claveDepartamental;
    }

    /**
     * @param string $clavePresupuestal
     * @return Departamento
     */
    public function setClavePresupuestal($clavePresupuestal)
    {
        $this->clavePresupuestal = $clavePresupuestal;

        return $this;
    }

    /**
     * @return string
     */
    public function getClavePresupuestal()
    {
        return $this->clavePresupuestal;
    }

    /**
     * @param boolean $esUnidad
     * @return Departamento
     */
    public function setEsUnidad($esUnidad)
    {
        $this->esUnidad = $esUnidad;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getEsUnidad()
    {
        return $this->esUnidad;
    }

    /**
     * @param integer $anio
     * @return Departamento
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param \DateTime $fecha
     * @return Departamento
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param Unidad $unidad
     * @return Departamento
     */
    public function setUnidad(Unidad $unidad = null)
    {
        $this->unidad = $unidad;

        return $this;
    }

    /**
     * @return Unidad
     */
    public function getUnidad()
    {
        return $this->unidad;
    }
}

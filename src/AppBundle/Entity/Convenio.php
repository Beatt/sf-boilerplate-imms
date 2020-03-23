<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="convenio")
 * @ORM\Entity
 */
class Convenio
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
     * @ORM\Column(type="string", length=250)
     */
    private $sector;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    private $tipo;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", length=250)
     */
    private $vigencia;

    /**
     * @var NivelAcademico
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\NivelAcademico")
     * @ORM\JoinColumn(name="nivel_id", referencedColumnName="id")
     */
    private $gradoAcademico;

    /**
     * @var CicloAcademico
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CicloAcademico")
     * @ORM\JoinColumn(name="ciclo_academico_id", referencedColumnName="id")
     */
    private $cicloAcademico;

    /**
     * @var Carrera
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Carrera")
     * @ORM\JoinColumn(name="carrera_id", referencedColumnName="id")
     */
    private $carrera;

    /**
     * @var Institucion
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Institucion")
     * @ORM\JoinColumn(name="institucion_id", referencedColumnName="id")
     */
    private $institucion;

    /**
     * @var Delegacion
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Delegacion")
     * @ORM\JoinColumn(name="delegacion_id", referencedColumnName="id")
     */
    private $delegacion;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $sector
     * @return Convenio
     */
    public function setSector($sector)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * @return string
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @param string $tipo
     * @return Convenio
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param \DateTime $vigencia
     * @return Convenio
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }

    /**
     * @param NivelAcademico $gradoAcademico
     * @return Convenio
     */
    public function setGradoAcademico(NivelAcademico $gradoAcademico = null)
    {
        $this->gradoAcademico = $gradoAcademico;

        return $this;
    }

    /**
     * @return NivelAcademico
     */
    public function getGradoAcademico()
    {
        return $this->gradoAcademico;
    }

    /**
     * @param CicloAcademico $cicloAcademico
     * @return Convenio
     */
    public function setCicloAcademico(CicloAcademico $cicloAcademico = null)
    {
        $this->cicloAcademico = $cicloAcademico;

        return $this;
    }

    /**
     * @return CicloAcademico
     */
    public function getCicloAcademico()
    {
        return $this->cicloAcademico;
    }

    /**
     * @param Carrera $carrera
     * @return Convenio
     */
    public function setCarrera(Carrera $carrera = null)
    {
        $this->carrera = $carrera;

        return $this;
    }

    /**
     * @return Carrera
     */
    public function getCarrera()
    {
        return $this->carrera;
    }

    /**
     * @param Institucion $institucion
     * @return Convenio
     */
    public function setInstitucion(Institucion $institucion = null)
    {
        $this->institucion = $institucion;

        return $this;
    }

    /**
     * @return Institucion
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * @param Delegacion $delegacion
     * @return Convenio
     */
    public function setDelegacion(Delegacion $delegacion = null)
    {
        $this->delegacion = $delegacion;

        return $this;
    }

    /**
     * @return Delegacion
     */
    public function getDelegacion()
    {
        return $this->delegacion;
    }
}

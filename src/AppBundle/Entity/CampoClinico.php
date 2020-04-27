<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="campo_clinico")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CampoClinicoRepository")
 */
class CampoClinico
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CicloAcademico")
     * @ORM\JoinColumn(name="ciclo_academico_id", referencedColumnName="id")
     */
    private $cicloAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Carrera")
     * @ORM\JoinColumn(name="carrera_id", referencedColumnName="id")
     */
    private $carrera;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaInicial;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaFinal;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $horario;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $promocion;

    /**
     * @ORM\Column(type="integer")
     */
    private $lugaresSolicitados;

    /**
     * @ORM\Column(type="integer")
     */
    private $lugaresAutorizados;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Convenio")
     * @ORM\JoinColumn(name="convenio_id", referencedColumnName="id")
     */
    private $convenio;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $referenciaBancaria;

    /**
     * @ORM\Column(type="float", precision=24, scale=4)
     */
    private $monto;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Solicitud", inversedBy="camposClinicos")
     * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
     */
    private $solicitud;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EstatusCampo")
     * @ORM\JoinColumn(name="estatus_campo_id", referencedColumnName="id")
     */
    private $estatus;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unidad")
     * @ORM\JoinColumn(name="unidad_id", referencedColumnName="id")
     */
    private $unidad;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $fechaInicial
     * @return CampoClinico
     */
    public function setFechaInicial($fechaInicial)
    {
        $this->fechaInicial = $fechaInicial;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    /**
     * @param \DateTime $fechaFinal
     * @return CampoClinico
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * @param string $horario
     * @return CampoClinico
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * @param string $promocion
     * @return CampoClinico
     */
    public function setPromocion($promocion)
    {
        $this->promocion = $promocion;

        return $this;
    }

    /**
     * @return string
     */
    public function getPromocion()
    {
        return $this->promocion;
    }

    /**
     * @param integer $lugaresSolicitados
     * @return CampoClinico
     */
    public function setLugaresSolicitados($lugaresSolicitados)
    {
        $this->lugaresSolicitados = $lugaresSolicitados;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLugaresSolicitados()
    {
        return $this->lugaresSolicitados;
    }

    /**
     * @param integer $lugaresAutorizados
     * @return CampoClinico
     */
    public function setLugaresAutorizados($lugaresAutorizados)
    {
        $this->lugaresAutorizados = $lugaresAutorizados;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLugaresAutorizados()
    {
        return $this->lugaresAutorizados;
    }

    /**
     * @param string $referenciaBancaria
     * @return CampoClinico
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
     * @param float $monto
     * @return CampoClinico
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
     * @param CicloAcademico $cicloAcademico
     * @return CampoClinico
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
     * @return CampoClinico
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
     * @param Convenio $convenio
     * @return CampoClinico
     */
    public function setConvenio(Convenio $convenio = null)
    {
        $this->convenio = $convenio;

        return $this;
    }

    /**
     * @return Convenio
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param Solicitud $solicitud
     * @return CampoClinico
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
     * @param EstatusCampo $estatus
     * @return CampoClinico
     */
    public function setEstatus(EstatusCampo $estatus = null)
    {
        $this->estatus = $estatus;
    }

    /**
     * @param Unidad $unidad
     * @return CampoClinico
     */
    public function setUnidad(Unidad $unidad = null)
    {
        $this->unidad = $unidad;
        return $this;
    }

    /**
     * @return EstatusCampo
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

     /**
      * @return Unidad
     */
    public function getUnidad()
    {
        return $this->unidad;
    }
}

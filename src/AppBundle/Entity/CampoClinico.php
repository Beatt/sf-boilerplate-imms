<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampoClinico
 *
 * @ORM\Table(name="campo_clinico")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CampoClinicoRepository")
 */
class CampoClinico
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
     * @var int
     *
     * @ORM\Column(name="ciclo_academico_id", type="integer")
     */
    private $cicloAcademicoId;

    /**
     * @var int
     *
     * @ORM\Column(name="carrera_id", type="integer")
     */
    private $carreraId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicial", type="date")
     */
    private $fechaInicial;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_final", type="date")
     */
    private $fechaFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="horario", type="string", length=100)
     */
    private $horario;

    /**
     * @var string
     *
     * @ORM\Column(name="promocion", type="string", length=100)
     */
    private $promocion;

    /**
     * @var int
     *
     * @ORM\Column(name="lugares_solicitados", type="integer")
     */
    private $lugaresSolicitados;

    /**
     * @var int
     *
     * @ORM\Column(name="lugares_autorizados", type="integer")
     */
    private $lugaresAutorizados;

    /**
     * @var int
     *
     * @ORM\Column(name="convenio_id", type="integer")
     */
    private $convenioId;

    /**
     * @var int
     *
     * @ORM\Column(name="solicitud_id", type="integer")
     */
    private $solicitudId;

    /**
     * @var string
     *
     * @ORM\Column(name="referencia_bancaria", type="string", length=100, nullable=true)
     */
    private $referenciaBancaria;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float", nullable=true)
     */
    private $monto;

    /**
     * @var int
     *
     * @ORM\Column(name="id_estatus", type="integer")
     */
    private $idEstatus;

    /**
     * @var int
     *
     * @ORM\Column(name="unidad_id", type="integer")
     */
    private $unidadId;


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
     * Set cicloAcademicoId
     *
     * @param integer $cicloAcademicoId
     *
     * @return CampoClinico
     */
    public function setCicloAcademicoId($cicloAcademicoId)
    {
        $this->cicloAcademicoId = $cicloAcademicoId;

        return $this;
    }

    /**
     * Get cicloAcademicoId
     *
     * @return int
     */
    public function getCicloAcademicoId()
    {
        return $this->cicloAcademicoId;
    }

    /**
     * Set carreraId
     *
     * @param integer $carreraId
     *
     * @return CampoClinico
     */
    public function setCarreraId($carreraId)
    {
        $this->carreraId = $carreraId;

        return $this;
    }

    /**
     * Get carreraId
     *
     * @return int
     */
    public function getCarreraId()
    {
        return $this->carreraId;
    }

    /**
     * Set fechaInicial
     *
     * @param \DateTime $fechaInicial
     *
     * @return CampoClinico
     */
    public function setFechaInicial($fechaInicial)
    {
        $this->fechaInicial = $fechaInicial;

        return $this;
    }

    /**
     * Get fechaInicial
     *
     * @return \DateTime
     */
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    /**
     * Set fechaFinal
     *
     * @param \DateTime $fechaFinal
     *
     * @return CampoClinico
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal
     *
     * @return \DateTime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * Set horario
     *
     * @param string $horario
     *
     * @return CampoClinico
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set promocion
     *
     * @param string $promocion
     *
     * @return CampoClinico
     */
    public function setPromocion($promocion)
    {
        $this->promocion = $promocion;

        return $this;
    }

    /**
     * Get promocion
     *
     * @return string
     */
    public function getPromocion()
    {
        return $this->promocion;
    }

    /**
     * Set lugaresSolicitados
     *
     * @param integer $lugaresSolicitados
     *
     * @return CampoClinico
     */
    public function setLugaresSolicitados($lugaresSolicitados)
    {
        $this->lugaresSolicitados = $lugaresSolicitados;

        return $this;
    }

    /**
     * Get lugaresSolicitados
     *
     * @return int
     */
    public function getLugaresSolicitados()
    {
        return $this->lugaresSolicitados;
    }

    /**
     * Set lugaresAutorizados
     *
     * @param integer $lugaresAutorizados
     *
     * @return CampoClinico
     */
    public function setLugaresAutorizados($lugaresAutorizados)
    {
        $this->lugaresAutorizados = $lugaresAutorizados;

        return $this;
    }

    /**
     * Get lugaresAutorizados
     *
     * @return int
     */
    public function getLugaresAutorizados()
    {
        return $this->lugaresAutorizados;
    }

    /**
     * Set convenioId
     *
     * @param integer $convenioId
     *
     * @return CampoClinico
     */
    public function setConvenioId($convenioId)
    {
        $this->convenioId = $convenioId;

        return $this;
    }

    /**
     * Get convenioId
     *
     * @return int
     */
    public function getConvenioId()
    {
        return $this->convenioId;
    }

    /**
     * Set solicitudId
     *
     * @param integer $solicitudId
     *
     * @return CampoClinico
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
     * Set referenciaBancaria
     *
     * @param string $referenciaBancaria
     *
     * @return CampoClinico
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
     * Set monto
     *
     * @param float $monto
     *
     * @return CampoClinico
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set idEstatus
     *
     * @param integer $idEstatus
     *
     * @return CampoClinico
     */
    public function setIdEstatus($idEstatus)
    {
        $this->idEstatus = $idEstatus;

        return $this;
    }

    /**
     * Get idEstatus
     *
     * @return int
     */
    public function getIdEstatus()
    {
        return $this->idEstatus;
    }

    /**
     * Set unidadId
     *
     * @param integer $unidadId
     *
     * @return CampoClinico
     */
    public function setUnidadId($unidadId)
    {
        $this->unidadId = $unidadId;

        return $this;
    }

    /**
     * Get unidadId
     *
     * @return int
     */
    public function getUnidadId()
    {
        return $this->unidadId;
    }
}


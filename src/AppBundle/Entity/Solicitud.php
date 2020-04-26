<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * Solicitud
 *
 * @ORM\Table(name="solicitud")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SolicitudRepository")
 */
class Solicitud
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="no_solicitud", type="string", length=9, unique=true, nullable=true)
     */
    private $noSolicitud;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $estatus;

    /**
     * @ORM\Column(name="referencia_bancaria", type="string", length=100, nullable=true)
     */
    private $referenciaBancaria;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CampoClinico", mappedBy="solicitud")
     */
    private $camposClinicos;

    public function __construct()
    {
        $this->fecha = new \DateTime();
        $this->camposClinicos = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $noSolicitud
     * @return Solicitud
     */
    public function setNoSolicitud($noSolicitud)
    {
        $this->noSolicitud = $noSolicitud;

        return $this;
    }

    /**
     * @return string
     */
    public function getNoSolicitud()
    {
        return $this->noSolicitud;
    }

    /**
     * @param \DateTime $fecha
     * @return Solicitud
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha->format('d/m/Y');
    }

    /**
     * @param string $estatus
     * @return Solicitud
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * @param string $referenciaBancaria
     * @return Solicitud
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
     * @return int
     * @throws Exception
     */
    public function getDemo()
    {
        return random_int(10, 10000);
    }

    public function getCampoClinicos()
    {
        return $this->camposClinicos;
    }

    public function getCamposClinicosSolicitados()
    {
        return count($this->getCampoClinicos());
    }

    public function getCamposClinicosAutorizados()
    {
        $acc = 0;
        foreach ($this->getCampoClinicos() as $campoClinico) {
            if($campoClinico->getLugaresAutorizados() > 0){
                $acc++;
            }
        }
        return $acc;
    }

    /**
     * @return string
     */
    public function getEstatusFormatted()
    {
        $result = '';
        switch ($this->getEstatus()){
            case 1:
                $result = 'Nueva'; break;
            case 2:
                $result = 'En espera de registro de montos'; break;
            case 3:
                $result = 'En espera de validación'; break;
            case 4:
                $result = 'Montos validados'; break;
            case 5:
                $result = 'Pago en proceso'; break;
            case 6:
                $result = 'En validación FOFOE'; break;
            case 7:
                $result = 'Pagado'; break;
        }
        return $result;
    }

    public function getInstitucion()
    {
        $result = '';
        $campos_clinicos = $this->getCampoClinicos();
        if($campos_clinicos->count() > 0){
            $result = $campos_clinicos[0]->getConvenio()->getInstitucion()->getNombre();
        }
        return $result;
    }

    /**
     * @return integer
     */
    public function getNoCamposSolicitados()
    {
        return $this->camposClinicos->count();
    }

    /**
     * @return integer
     */
    public function getNoCamposAutorizados()
    {
        /** @var CampoClinico $campoClinico */
        $noCamposSolicitados = array_filter($this->getCampoClinicos()->toArray(), function (CampoClinico $campoClinico) {
            return $campoClinico->getEstatus()->getEstatus() === EstatusCampo::SOLICITUD_CREADA;
        });

        return count($noCamposSolicitados);
    }
}

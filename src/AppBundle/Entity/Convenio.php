<?php

namespace AppBundle\Entity;

use Carbon\Carbon;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @ORM\Table(name="convenio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConvenioRepository")
 * @Assert\GroupSequenceProvider
 * @UniqueEntity(
 *     groups={"Específico"},
 *     fields={ "institucion", "carrera", "cicloAcademico", "gradoAcademico", "vigencia"},
 *     errorPath="institucion",
 *     message="La institución ya tiene registrado un convenio específico con esa vigencia."
 * )
 * @UniqueEntity(
 *     groups={"General"},
 *     fields={"institucion", "vigencia"},
 *     errorPath="institucion",
 *     message="La institución ya tiene registrado un convenio general con esa vigencia."
 * )
 */
class Convenio implements GroupSequenceProviderInterface
{
    const SECTOR_PUBLICO = "Público";
    const SECTOR_PRIVADO = "Privado";
    const TIPO_GENERAL = "General";
    const TIPO_ESPECIFICO = "Específico";

    const SECTORES = [self::SECTOR_PUBLICO, self::SECTOR_PRIVADO];
    const TIPOS = [self::TIPO_GENERAL, self::TIPO_ESPECIFICO];

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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank
     * @Assert\Choice(choices=Convenio::SECTORES, message="Sector debe ser Público o Privado")
     */
    private $sector;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank
     * @Assert\Choice(choices=Convenio::TIPOS, message="Tipo debe ser General o Específico")
     */
    private $tipo;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Vigencia no debe estar vacìo o debe ser una fecha válida con formato: AAAA-MM-DD")
     */
    private $vigencia;

    /**
     * @var CicloAcademico
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CicloAcademico")
     * @ORM\JoinColumn(name="ciclo_academico_id", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank(groups={Convenio::TIPO_ESPECIFICO})
     */
    private $cicloAcademico;

    /**
     * @var Carrera
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Carrera")
     * @ORM\JoinColumn(name="carrera_id", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank(groups={Convenio::TIPO_ESPECIFICO},
        message="Este valor debería ser un valor del catálogo de Carreras.") */
    private $carrera;

    /**
     * @var Institucion
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Institucion")
     * @ORM\JoinColumn(name="institucion_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $institucion;

    /**
     * @var Delegacion
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Delegacion")
     * @ORM\JoinColumn(name="delegacion_id", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private $delegacion;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $numero;

    /**
     * @var CampoClinico
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CampoClinico", mappedBy="convenio")
     */
    private $camposClinicos;

    public function __construct()
    {
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * @param DateTime $vigencia
     * @return Convenio
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getVigencia()
    {
        return $this->vigencia;
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

    /**
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param string $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getLabel()
    {
        if($this->vigencia > Carbon::now()->addMonths(12)) {
            return 'green';
        }
        if(
            $this->vigencia > Carbon::now()->addMonths(6) AND
            $this->vigencia <= Carbon::now()->addMonths(12)
        ) {
            return 'yellow';
        }

        return 'red';
    }

    public function getGroupSequence()
    {
        return [
            'Convenio',
            $this->tipo,
        ];
    }

    public function getCampoClinicos()
    {
        return $this->camposClinicos;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}

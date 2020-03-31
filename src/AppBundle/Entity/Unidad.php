<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Property\CoordinatesProperty;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="unidad")
 * @ORM\Entity
 */
class Unidad
{
    use CoordinatesProperty;

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
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=12)
     * @Assert\Length(
     *      max = 12,
     *      maxMessage = "No puede contener más de {{ limit }} carácteres"
     * )
     */
    private $claveUnidad;

    /**
     * @var Delegacion
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Delegacion")
     * @ORM\JoinColumn(name="delegacion_id", referencedColumnName="id")
     */
    private $delegacion;

    /**
     * @var string
     * @ORM\Column(type="string", length=12)
     * @Assert\Length(
     *      max = 12,
     *      maxMessage = "No puede contener más de {{ limit }} carácteres"
     * )
     */
    private $clavePresupuestal;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $nivelAtencion;

    /**
     * @var TipoUnidad
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TipoUnidad")
     * @ORM\JoinColumn(name="tipo_unidad_id", referencedColumnName="id")
     */
    private $tipoUnidad;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $esUmae;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    private $nombreUnidadPrincipal;

    /**
     * @var string
     * @ORM\Column(type="string", length=2)
     * @Assert\Length(
     *      max = 2,
     *      maxMessage = "No puede contener más de {{ limit }} carácteres"
     * )
     */
    private $claveUnidadPrincipal;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @Assert\Length(
     *     max = 4,
     *     min = 4,
     *     maxMessage = "No puede contener más de {{ limit }} carácteres",
     *     minMessage = "No puede contener menos de {{ limit }} carácteres"
     * )
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
     * @param string $nombre
     * @return Unidad
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
     * @param string $claveUnidad
     * @return Unidad
     */
    public function setClaveUnidad($claveUnidad)
    {
        $this->claveUnidad = $claveUnidad;

        return $this;
    }

    /**
     * @return string
     */
    public function getClaveUnidad()
    {
        return $this->claveUnidad;
    }

    /**
     * @param string $clavePresupuestal
     * @return Unidad
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
     * @param integer $nivelAtencion
     * @return Unidad
     */
    public function setNivelAtencion($nivelAtencion)
    {
        $this->nivelAtencion = $nivelAtencion;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNivelAtencion()
    {
        return $this->nivelAtencion;
    }

    /**
     * @param boolean $esUmae
     * @return Unidad
     */
    public function setEsUmae($esUmae)
    {
        $this->esUmae = $esUmae;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getEsUmae()
    {
        return $this->esUmae;
    }

    /**
     * @param string $direccion
     * @return Unidad
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param string $nombreUnidadPrincipal
     * @return Unidad
     */
    public function setNombreUnidadPrincipal($nombreUnidadPrincipal)
    {
        $this->nombreUnidadPrincipal = $nombreUnidadPrincipal;

        return $this;
    }

    /**
     * @return string
     */
    public function getNombreUnidadPrincipal()
    {
        return $this->nombreUnidadPrincipal;
    }

    /**
     * @param string $claveUnidadPrincipal
     * @return Unidad
     */
    public function setClaveUnidadPrincipal($claveUnidadPrincipal)
    {
        $this->claveUnidadPrincipal = $claveUnidadPrincipal;

        return $this;
    }

    /**
     * @return string
     */
    public function getClaveUnidadPrincipal()
    {
        return $this->claveUnidadPrincipal;
    }

    /**
     * @param integer $anio
     * @return Unidad
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
     * @return Unidad
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
     * @param boolean $activo
     * @return Unidad
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
     * @param Delegacion $delegacion
     * @return Unidad
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
     * @param TipoUnidad $tipoUnidad
     * @return Unidad
     */
    public function setTipoUnidad(TipoUnidad $tipoUnidad = null)
    {
        $this->tipoUnidad = $tipoUnidad;

        return $this;
    }

    /**
     * @return TipoUnidad
     */
    public function getTipoUnidad()
    {
        return $this->tipoUnidad;
    }
}

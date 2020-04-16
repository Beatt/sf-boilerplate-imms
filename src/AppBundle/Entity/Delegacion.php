<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Property\CoordinatesProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="delegacion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DelegationRepository")
 */
class Delegacion
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
     * @ORM\Column(type="string", length=30)
     */
    private $nombre;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @var string
     * @ORM\Column(type="string", length=2)
     * @Assert\Length(
     *     max = 2,
     *     min = 2,
     *     maxMessage = "No puede contener más de {{ limit }} carácteres",
     *     minMessage = "No puede contener menos de {{ limit }} carácteres"
     * )
     */
    private $claveDelegacional;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", nullable=true)
     */
    private $region;

    /**
     * @var string
     * @ORM\Column(type="string", length=4)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "No puede contener más de {{ limit }} carácteres"
     * )
     */
    private $grupoDelegacion;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $nombreGrupoDelegacion;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Usuario", mappedBy="delegaciones")
     */
    private $usuarios;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    public function __construct()
    {
        $this->fecha = new \DateTime();
        $this->usuarios = new ArrayCollection();
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
     * @return Delegacion
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
     * @return Delegacion
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
     * @param string $claveDelegacional
     * @return Delegacion
     */
    public function setClaveDelegacional($claveDelegacional)
    {
        $this->claveDelegacional = $claveDelegacional;

        return $this;
    }

    /**
     * @return string
     */
    public function getClaveDelegacional()
    {
        return $this->claveDelegacional;
    }

    /**
     * @param string $grupoDelegacion
     * @return Delegacion
     */
    public function setGrupoDelegacion($grupoDelegacion)
    {
        $this->grupoDelegacion = $grupoDelegacion;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrupoDelegacion()
    {
        return $this->grupoDelegacion;
    }

    /**
     * @param string $nombreGrupoDelegacion
     * @return Delegacion
     */
    public function setNombreGrupoDelegacion($nombreGrupoDelegacion)
    {
        $this->nombreGrupoDelegacion = $nombreGrupoDelegacion;

        return $this;
    }

    /**
     * @return string
     */
    public function getNombreGrupoDelegacion()
    {
        return $this->nombreGrupoDelegacion;
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
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param Region $region
     * @return Delegacion
     */
    public function setRegion(Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Usuario $usuario
     * @return Delegacion
     */
    public function addUsuario(Usuario $usuario)
    {
        if(!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
            $usuario->addDelegacione($this);
        }

        return $this;
    }

    /**
     * @param Usuario $usuario
     */
    public function removeUsuario(Usuario $usuario)
    {
        if($this->usuarios->contains($usuario)) {
            $this->usuarios->removeElement($usuario);
            $usuario->removeDelegacione($this);
        }
    }

    /**
     * @return Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
}

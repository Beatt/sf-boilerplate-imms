<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="permiso")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PermissionRepository")
 */
class Permiso
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
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $clave;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rol", inversedBy="permisos")
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     */
    private $rol;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Usuario", mappedBy="permisos")
     */
    private $usuarios;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Permiso
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return string
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * @param string $clave
     */
    public function setClave($clave)
    {
        $this->clave = $clave;
    }

    /**
     * @return Rol
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param Rol $rol
     */
    public function setRol(Rol $rol)
    {
        $this->rol = $rol;
    }

    /**
     * @param Usuario $usuario
     * @return Permiso
     */
    public function addUsuario(Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * @param Usuario $usuario
     */
    public function removeUsuario(Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * @return Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    public function __toString()
    {
      return $this->clave;
    }
}

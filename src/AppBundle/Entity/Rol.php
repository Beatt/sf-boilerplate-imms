<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rol")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Rol
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Usuario", mappedBy="roles")
     */
    private $usuarios;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Permiso", mappedBy="roles")
     */
    private $permisos;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->permisos = new ArrayCollection();
    }

    /**
     * @return int
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
     * @param Usuario $usuario
     * @return Rol
     */
    public function addUsuario(Usuario $usuario)
    {
        if(!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
        }

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
     * @return ArrayCollection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * @param Permiso $permiso
     * @return Rol
     */
    public function addPermiso(Permiso $permiso)
    {
        if(!$this->permisos->contains($permiso)) {
            $this->permisos[] = $permiso;
        }

        return $this;
    }

    /**
     * @param Permiso $permiso
     */
    public function removePermiso(Permiso $permiso)
    {
        $this->permisos->removeElement($permiso);
    }

    /**
     * @return ArrayCollection
     */
    public function getPermisos()
    {
        return $this->permisos;
    }
}

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
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80)
     */
    private $rolSeguridad;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Rol", inversedBy="permisos")
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Permiso
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set rolSeguridad
     *
     * @param string $rolSeguridad
     *
     * @return Permiso
     */
    public function setRolSeguridad($rolSeguridad)
    {
        $this->rolSeguridad = $rolSeguridad;

        return $this;
    }

    /**
     * Get rolSeguridad
     *
     * @return string
     */
    public function getRolSeguridad()
    {
        return $this->rolSeguridad;
    }

    /**
     * @param Rol $role
     * @return Permiso
     */
    public function addRole(Rol $role)
    {
        if(!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param Rol $role
     */
    public function removeRole(Rol $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * @return Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }
}

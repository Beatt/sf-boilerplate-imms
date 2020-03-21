<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class Usuario implements UserInterface
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
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $contrasena;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     */
    private $correo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $esActiva;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Rol", inversedBy="usuarios")
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     */
    private $roles;

    /** @var string */
    private $plainPassword;

    public function __construct()
    {
        $this->esActiva = true;
        $this->roles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * @param mixed $contrasena
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }

    /**
     * @return bool
     */
    public function isEsActiva()
    {
        return $this->esActiva;
    }

    /**
     * @param bool $esActiva
     */
    public function setEsActiva($esActiva)
    {
        $this->esActiva = $esActiva;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $securityRoles = [];

        /** @var Rol $role */
        foreach($this->roles as $role) {

            /** @var Permiso $permiso */
            foreach($role->getPermisos() as $permiso) {
                $securityRoles[] = $permiso->getRolSeguridad();
            }
        }

        return $securityRoles;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->contrasena;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->correo;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Get esActiva
     *
     * @return boolean
     */
    public function getEsActiva()
    {
        return $this->esActiva;
    }

    /**
     * @param Rol $role
     * @return Usuario
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
}

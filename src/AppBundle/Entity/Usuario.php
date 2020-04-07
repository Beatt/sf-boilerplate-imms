<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var integer
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Length(
     *     min="6",
     *     max="15",
     *     minMessage="Este valor es demasiado corto. Debería tener {{ limit }} caracteres o más.",
     *     maxMessage="Este valor es demasiado largo. Debería tener {{ limit }} caracteres o menos."
     * )
     */
    private $matricula;

    /**
     * @var string
     * @ORM\Column(type="string", length=25)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $apellidoPaterno;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $apellidoMaterno;

    /**
     * @var integer
     * @ORM\Column(type="bigint")
     */
    private $regims;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $contrasena;

    /**
     * @var string
     * @Assert\Email()
     * @ORM\Column(type="string", length=254, unique=true)
     */
    private $correo;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Departamento", inversedBy="usuarios")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
     */
    private $departamento;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Delegacion", inversedBy="usuariosPorDelegacion")
     * @ORM\JoinColumn(name="delegacion_id", referencedColumnName="id")
     */
    private $delegaciones;

    /**
     * @var string
     * @ORM\Column(type="string", length=18)
     * @Assert\Length(
     *     min="18",
     *     max="18",
     *     minMessage="Este valor es demasiado corto. Debería tener {{ limit }} caracteres o más.",
     *     maxMessage="Este valor es demasiado largo. Debería tener {{ limit }} caracteres o menos."
     * )
     */
    private $curp;

    /**
     * @var string
     * @ORM\Column(type="string", length=13)
     * @Assert\Length(
     *     min="13",
     *     max="13",
     *     minMessage="Este valor es demasiado corto. Debería tener {{ limit }} caracteres o más.",
     *     maxMessage="Este valor es demasiado largo. Debería tener {{ limit }} caracteres o menos."
     * )
     */
    private $rfc;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $sexo;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Rol", inversedBy="usuarios")
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     */
    private $rols;

    /** @var string */
    private $plainPassword;

    public function __construct()
    {
        $this->rols = new ArrayCollection();
        $this->delegaciones = new ArrayCollection();
        $this->fechaIngreso = new \DateTime();
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
        foreach($this->rols as $role) {

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
     * @param Rol $role
     * @return Usuario
     */
    public function addRol(Rol $role)
    {
        if(!$this->rols->contains($role)) {
            $this->rols[] = $role;
        }

        return $this;
    }

    /**
     * @param Rol $role
     */
    public function removeRol(Rol $role)
    {
        $this->rols->removeElement($role);
    }

    /**
     * @return ArrayCollection
     */
    public function getRols()
    {
        return $this->rols;
    }

    /**
     * @param integer $matricula
     * @return Usuario
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param string $nombre
     * @return Usuario
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
     * @param string $apellidoPaterno
     * @return Usuario
     */
    public function setApellidoPaterno($apellidoPaterno)
    {
        $this->apellidoPaterno = $apellidoPaterno;

        return $this;
    }

    /**
     * @return string
     */
    public function getApellidoPaterno()
    {
        return $this->apellidoPaterno;
    }

    /**
     * @param string $apellidoMaterno
     * @return Usuario
     */
    public function setApellidoMaterno($apellidoMaterno)
    {
        $this->apellidoMaterno = $apellidoMaterno;

        return $this;
    }

    /**
     * @return string
     */
    public function getApellidoMaterno()
    {
        return $this->apellidoMaterno;
    }

    /**
     * @param integer $regims
     * @return Usuario
     */
    public function setRegims($regims)
    {
        $this->regims = $regims;

        return $this;
    }

    /**
     * @return integer
     */
    public function getRegims()
    {
        return $this->regims;
    }

    /**
     * @param boolean $activo
     * @return Usuario
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
     * @param string $curp
     * @return Usuario
     */
    public function setCurp($curp)
    {
        $this->curp = $curp;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurp()
    {
        return $this->curp;
    }

    /**
     * @param string $rfc
     * @return Usuario
     */
    public function setRfc($rfc)
    {
        $this->rfc = $rfc;

        return $this;
    }

    /**
     * @return string
     */
    public function getRfc()
    {
        return $this->rfc;
    }

    /**
     * @param string $sexo
     * @return Usuario
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @param \DateTime $fechaIngreso
     * @return Usuario
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param Departamento $departamento
     * @return Usuario
     */
    public function setDepartamento(Departamento $departamento)
    {
        $this->departamento = $departamento;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param Categoria $categoria
     * @return Usuario
     */
    public function setCategoria(Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * @return Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $fullName = $this->nombre;

        if($this->apellidoPaterno !== null) {
            $fullName .= " {$this->apellidoPaterno}";
        }

        if($this->apellidoMaterno !== null) {
            $fullName .= " {$this->apellidoMaterno}";
        }

        return $fullName;
    }

    /**
     * @return string
     */
    public function getAssignRoles()
    {
        $roles = [];

        /** @var Rol $rol */
        foreach($this->rols as $rol) {
            array_push($roles, $rol->getNombre());
        }

        return implode($roles);
    }

   /* public function getAssignDepartments()
    {
        $departments = [];

        foreach($this->departamentos as $departamento) {
            array_push($departments, $departamento->getNombre());
        }

        return implode($departments);
    }*/


    /**
     * @param Delegacion $delegacione
     * @return Usuario
     */
    public function addDelegacione(Delegacion $delegacione)
    {
        if(!$this->delegaciones->contains($delegacione)) {
            $this->delegaciones[] = $delegacione;
            $delegacione->addUsuario($this);
        }

        return $this;
    }

    /**
     * @param Delegacion $delegacione
     */
    public function removeDelegacione(Delegacion $delegacione)
    {
        if($this->delegaciones->contains($delegacione)) {
            $this->delegaciones->removeElement($delegacione);
            $delegacione->removeUsuario($this);
        }
    }

    /**
     * @return Collection
     */
    public function getDelegaciones()
    {
        return $this->delegaciones;
    }
}

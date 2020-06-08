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
     * @ORM\Column(type="integer", unique=true, nullable=true)
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Permiso", inversedBy="usuarios")
     * @ORM\JoinColumn(name="permiso_id", referencedColumnName="id")
     */
    private $permisos;

    /** @var string */
    private $plainPassword;

    /**
     * @var Institucion
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Institucion", mappedBy="usuario")
     */
    private $institucion;

    public function __construct()
    {
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
        $roles = [];

        /** @var Permiso $permiso */
        foreach($this->getPermisos() as $permiso) {
            $roles[] = sprintf('ROLE_%s', $permiso->getClave());
        }

        return $roles;
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
     * @return Departamento
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

    /**
     * @return Institucion
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * @param Permiso $permiso
     * @return Usuario
     */
    public function addPermiso(Permiso $permiso)
    {
        $this->permisos[] = $permiso;

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
     * @return Collection
     */
    public function getPermisos()
    {
        return $this->permisos;
    }

    /**
     * @param Institucion $institucion
     * @return Usuario
     */
    public function setInstitucion(Institucion $institucion = null)
    {
        $this->institucion = $institucion;

        return $this;
    }
}

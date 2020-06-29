<?php

namespace AppBundle\Entity;

use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="institucion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InstitucionRepository")
 * @UniqueEntity("correo")
 * @Vich\Uploadable
 */
class Institucion
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *  min="10",
     *  max="255",
     *  minMessage="Este valor es demasiado corto. Debería tener {{ limit }} caracteres o más.",
     *  maxMessage="Este valor es demasiado largo. Debería tener {{ limit }} caracteres o menos."
     * )
     */
    protected $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=16, nullable=true)
     * @Assert\Regex(
     *  pattern="/^(0|[1-9][0-9]*)$/",
     *  message="Solo se pueden ingresar números"
     * )
     * @Assert\Length(
     *  min="8",
     *  max="10",
     *  minMessage="Este valor es demasiado corto. Debería tener {{ limit }} caracteres.",
     *  maxMessage="Este valor es demasiado largo. Debería tener {{ limit }} caracteres."
     * )
     */
    protected $telefono;

    /**
     * @var string
     * @ORM\Column(type="string", length=254, nullable=true)
    */
    protected $correo;

    /**
     * @var string
     * @ORM\Column(type="string", length=254, nullable=true)
     */
    protected $fax;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $sitioWeb;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $cedulaIdentificacion;

    /**
     * @Vich\UploadableField(mapping="institucion_cedulas", fileNameProperty="cedulaIdentificacion")
     * @Assert\File(
     *  maxSize="1000000",
     *  mimeTypes = {"application/pdf", "application/x-pdf"},
     *  mimeTypesMessage = "Solo se admiten archivos PDF"
     * )
     * @var File
     */
    protected $cedulaFile;

    /**
     * @var string
     * @ORM\Column(type="string", length=13, nullable=true)
     * @Assert\Length(
     *     min="12",
     *     max="13",
     *     minMessage="Este valor es demasiado corto. Debería tener {{ limit }} caracteres o más.",
     *     maxMessage="Este valor es demasiado largo. Debería tener {{ limit }} caracteres o menos."
     * )
     */
    protected $rfc;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *  message="Este campo no puede estar vacio"
     * )
     */
    protected $direccion;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $representante;

    /**
     * @var Convenio
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Convenio", mappedBy="institucion")
     */
    protected $convenios;

    /**
     * @var Usuario
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="institucion")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fechaCedulaIdentificacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $confirmacionInformacion;

    /**
     * @var string
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(
     *     max="5",
     *     minMessage="Este valor es demasiado corto. Debería tener {{ limit }} caracteres o más.",
     *     maxMessage="Este valor es demasiado largo. Debería tener {{ limit }} caracteres o menos."
     * )
     */
    protected $extension;

    public function __construct()
    {
        $this->convenios = new ArrayCollection();
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
     * @return Institucion
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
     * @param string $telefono
     * @return Institucion
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $correo
     * @return Institucion
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param string $fax
     * @return Institucion
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $sitioWeb
     * @return Institucion
     */
    public function setSitioWeb($sitioWeb)
    {
        $this->sitioWeb = $sitioWeb;

        return $this;
    }

    /**
     * @return string
     */
    public function getSitioWeb()
    {
        return $this->sitioWeb;
    }

    /**
     * @param string $cedulaIdentificacion
     * @return Institucion
     */
    public function setCedulaIdentificacion($cedulaIdentificacion)
    {
        $this->cedulaIdentificacion = $cedulaIdentificacion;

        return $this;
    }

    /**
     * @return string
     */
    public function getCedulaIdentificacion()
    {
        return $this->cedulaIdentificacion;
    }

    /**
     * @param string $rfc
     * @return Institucion
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
     * @param string $direccion
     * @return Institucion
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
     * @param string $representante
     * @return Institucion
     */
    public function setRepresentante($representante)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * @return File
     */
    public function getCedulaFile()
    {
        return $this->cedulaFile;
    }

    /**
     * @param File $cedulaFile
     */
    public function setCedulaFile($cedulaFile)
    {
        $this->cedulaFile = $cedulaFile;

        $this->setFechaCedulaIdentificacion(Carbon::now());
    }

    /**
     * @param Convenio $convenio
     * @return Institucion
     */
    public function addConvenio(Convenio $convenio)
    {
        $this->convenios[] = $convenio;

        return $this;
    }

    /**
     * @param Convenio $convenio
     */
    public function removeConvenio(Convenio $convenio)
    {
        $this->convenios->removeElement($convenio);
    }

    /**
     * @return Collection
     */
    public function getConvenios()
    {
        return $this->convenios;
    }

    /**
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     * @return Institucion
     */
    public function setUsuario(Usuario $usuario = null)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFechaCedulaIdentificacion()
    {
        return $this->fechaCedulaIdentificacion;
    }

    /**
     * @param DateTime $fechaCedulaIdentificacion
     */
    public function setFechaCedulaIdentificacion($fechaCedulaIdentificacion)
    {
        $this->fechaCedulaIdentificacion = $fechaCedulaIdentificacion;
    }

    /**
     * @return DateTime
     */
    public function getConfirmacionInformacion()
    {
        return $this->confirmacionInformacion;
    }

    /**
     * @param DateTime $confirmacionInformacion
     */
    public function setConfirmacionInformacion($confirmacionInformacion)
    {
        $this->confirmacionInformacion = $confirmacionInformacion;
    }

    /**
     * @return bool
     */
    public function isConfirmacionInformacion()
    {
        return $this->confirmacionInformacion !== null;
    }
}

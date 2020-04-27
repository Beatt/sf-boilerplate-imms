<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="institucion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InstitucionRepository")
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
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     * @ORM\Column(type="string", length=254, nullable=true)
     * @Assert\Email()
     */
    private $correo;

    /**
     * @var string
     * @ORM\Column(type="string", length=254, nullable=true)
     */
    private $fax;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sitioWeb;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cedulaIdentificacion;

    /**
     * @Vich\UploadableField(mapping="institucion_cedulas", fileNameProperty="cedulaIdentificacion")
     * @Assert\File(
     *     maxSize="1000000",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     * )
     * @var File
     */
    private $cedulaFile;

    /**
     * @var string
     * @ORM\Column(type="string", length=13, nullable=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $representante;

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
        $this->direccion = $representante;

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
     * @param UploadedFile $cedulaFile
     */
    public function setCedulaFile($cedulaFile)
    {
        $this->cedulaFile = $cedulaFile;
    }
}

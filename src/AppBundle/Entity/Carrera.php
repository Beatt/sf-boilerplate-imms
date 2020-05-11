<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="carrera")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarreraRepository")
 */
class Carrera
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
     * @ORM\Column(type="string", length=35)
     */
    private $nombre;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @var NivelAcademico
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\NivelAcademico", cascade={"persist"})
     * @ORM\JoinColumn(name="nivel_academico_id", referencedColumnName="id")
     */
    private $nivelAcademico;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $nombre
     * @return Carrera
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
     * @return Carrera
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
     * @param NivelAcademico $nivelAcademico
     * @return Carrera
     */
    public function setNivelAcademico(NivelAcademico $nivelAcademico = null)
    {
        $this->nivelAcademico = $nivelAcademico;

        return $this;
    }

    /**
     * @return NivelAcademico
     */
    public function getNivelAcademico()
    {
        return $this->nivelAcademico;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
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
     * @ORM\JoinColumn(name="nivel_academico_id", referencedColumnName="id", nullable=false)
     */
    private $nivelAcademico;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MontoCarrera", mappedBy="carrera")
     */
    private $montosCarreras;

    public function __construct()
    {
        $this->montosCarreras = new \Doctrine\Common\Collections\ArrayCollection();
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
   * @return string
   */
    public function getDisplayName() {
      return $this->nivelAcademico->getNombre()
          . " - " . $this->nombre;
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

    /**
     * @param MontoCarrera $montosCarrera
     * @return Carrera
     */
    public function addMontosCarrera(MontoCarrera $montosCarrera)
    {
        $this->montosCarreras[] = $montosCarrera;

        return $this;
    }

    /**
     * @param MontoCarrera $montosCarrera
     */
    public function removeMontosCarrera(MontoCarrera $montosCarrera)
    {
        $this->montosCarreras->removeElement($montosCarrera);
    }

    /**
     * @return Collection
     */
    public function getMontosCarreras()
    {
        return $this->montosCarreras;
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tipo_unidad")
 * @ORM\Entity
 */
class TipoUnidad
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
     * @ORM\Column(type="string", length=150)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $descripcion;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    private $grupoTipo;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $grupoNombre;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $nombre
     * @return TipoUnidad
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
     * @return TipoUnidad
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
     * @param string $descripcion
     * @return TipoUnidad
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $grupoTipo
     * @return TipoUnidad
     */
    public function setGrupoTipo($grupoTipo)
    {
        $this->grupoTipo = $grupoTipo;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrupoTipo()
    {
        return $this->grupoTipo;
    }

    /**
     * @param string $grupoNombre
     * @return TipoUnidad
     */
    public function setGrupoNombre($grupoNombre)
    {
        $this->grupoNombre = $grupoNombre;

        return $this;
    }

    /**
     * @return string
     */
    public function getGrupoNombre()
    {
        return $this->grupoNombre;
    }
}

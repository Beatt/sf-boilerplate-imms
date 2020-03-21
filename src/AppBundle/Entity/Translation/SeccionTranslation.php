<?php

namespace AppBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="seccion_traduccion")
 * @ORM\Entity
 */
class SeccionTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $descripcion;

    /** @var string */
    private $modulo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Translation\Seccion")
     * @ORM\JoinColumn(name="translatable_id", referencedColumnName="id")
     */
    private $seccion;

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param string $modulo
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;
    }

    /**
     * @return mixed
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * @param mixed $seccion
     */
    public function setSeccion($seccion)
    {
        $this->seccion = $seccion;
    }
}

<?php

namespace AppBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="seccion")
 * @ORM\Entity
 */
class Seccion
{
    use ORMBehaviors\Translatable\Translatable;

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
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $modulo;

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
}

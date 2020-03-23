<?php

namespace AppBundle\Entity\Property;

trait CoordinatesProperty
{
    /**
     * @var float
     * @ORM\Column(type="decimal", precision=24, scale=4)
     */
    private $latitud;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=24, scale=4)
     */
    private $altitud;

    /**
     * @param string $latitud
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }

    /**
     * @return string
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * @param string $altitud
     */
    public function setAltitud($altitud)
    {
        $this->altitud = $altitud;
    }

    /**
     * @return string
     */
    public function getAltitud()
    {
        return $this->altitud;
    }
}

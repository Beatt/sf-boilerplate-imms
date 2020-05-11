<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="monto_carrera")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MontoCarreraRepository")
 */
class MontoCarrera
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
     * @ORM\Column(name="monto_inscripcion", type="float", precision=24, scale=4)
     */
    private $montoInscripcion;

    /**
     * @ORM\Column(name="monto_colegiatura", type="float", precision=24, scale=4)
     */
    private $montoColegiatura;

    /**
     * @var Solicitud
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Solicitud")
     * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
     */
    private $solicitud;

    /**
     * @var Carrera
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Carrera")
     * @ORM\JoinColumn(name="carrera_id", referencedColumnName="id")
     */
    private $carrera;
<<<<<<< HEAD



=======
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param float $montoColegiatura
     * @return MontoCarrera
     */
    public function setMontoColegiatura($montoColegiatura)
    {
        $this->montoColegiatura = $montoColegiatura;

        return $this;
    }

    /**
     * @return float
     */
    public function getMontoColegiatura()
    {
        return $this->montoColegiatura;
    }


    /**
     * @param float $montoInscripcion
     * @return MontoCarrera
     */
    public function setMontoInscripcion($montoInscripcion)
    {
        $this->montoInscripcion = $montoInscripcion;

        return $this;
    }

    /**
     * @return float
     */
    public function getMontoInscripcion()
    {
        return $this->montoInscripcion;
    }


    /**
     * @param Solicitud $solicitud
     * @return MontoCarrera
     */
    public function setSolicitud($solicitud)
    {
        $this->solicitud = $solicitud;
        return $this;
    }

    /**
     * @return Solicitud
     */
    public function getSolicitud()
    {
        return $this->solicitud;
    }

<<<<<<< HEAD

=======
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
    /**
     * @param Carrera $carrera
     * @return MontoCarrera
     */
<<<<<<< HEAD
    public function setCarrera($carrera)
    {
        $this->carrera = $carrera;
=======
    public function setCarrera(Carrera $carrera = null)
    {
        $this->carrera = $carrera;

>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
        return $this;
    }

    /**
     * @return Carrera
     */
    public function getCarrera()
    {
        return $this->carrera;
    }
}

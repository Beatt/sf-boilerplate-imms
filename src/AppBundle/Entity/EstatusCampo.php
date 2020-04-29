<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="estatus_campo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EstatusCampoRepository")
 */
class EstatusCampo
{
    const SOLICITUD_NO_AUTORIZADA = 'solicitud_no_autorizada';
    const MONTOS_VALIDADOS = 'montos_validados';
    const FORMATOS_DE_PAGO_GENERADOS = 'formatos_de_pago_generados';
    const EN_VALIDACION_POR_FOFOE = 'en_validacion_por_fofoe';
    const PAGO_NO_VALIDO = 'pago_no_valido';
    const PENDIENTE_CFDI_POR_FOFOE = 'pendiente_cfdi_por_fofoe';
    const PAGO_VALIDADO = 'pago_validado';
    const CREDENCIALES_GENERADAS = 'credenciales_generadas';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $estatus;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $estatus
     * @return EstatusCampo
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * @param string $nombre
     * @return EstatusCampo
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
}

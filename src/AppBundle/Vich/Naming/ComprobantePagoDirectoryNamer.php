<?php

namespace AppBundle\Vich\Naming;

use AppBundle\Entity\Pago;
use AppBundle\Repository\InstitucionRepositoryInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class ComprobantePagoDirectoryNamer implements DirectoryNamerInterface
{
    /**
     * @var InstitucionRepositoryInterface
     */
    private $institucionRepository;

    public function __construct(InstitucionRepositoryInterface $institucionRepository)
    {
        $this->institucionRepository = $institucionRepository;
    }

    /**
     * @param Pago $object
     * @param PropertyMapping $mapping
     * @return string|void
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        $institucion = $this->institucionRepository->getInstitucionBySolicitudId(
            $object->getSolicitud()->getId()
        );

        return $institucion->getNombre();
    }
}

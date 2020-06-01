<?php

namespace AppBundle\Vich\Naming;

use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Repository\InstitucionRepositoryInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class InstitucionDirectoryNamer implements DirectoryNamerInterface
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
     * @param $object
     * @param PropertyMapping $mapping
     * @return string|void
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        $id = null;

        if($object instanceof Pago) $id = $object->getSolicitud()->getId();
        elseif($object instanceof Solicitud) $id = $object->getId();

        $institucion = $this->institucionRepository->getInstitucionBySolicitudId($id);
        return $institucion->getNombre();
    }
}

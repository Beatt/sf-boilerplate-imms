<?php

namespace AppBundle\Vich\Naming;

use AppBundle\Entity\Institucion;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Repository\InstitucionRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class InstitucionDirectoryNamer implements DirectoryNamerInterface
{
    private $institucionRepository;

    private $tokenStorage;

    public function __construct(
        InstitucionRepositoryInterface $institucionRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->institucionRepository = $institucionRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $object
     * @param PropertyMapping $mapping
     * @return string|void
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        if($object instanceof Institucion) {
            /** @var Institucion $institucion */
            $institucion = $this->tokenStorage->getToken()->getUser()->getInstitucion();
            return $institucion->getNombre();
        }

        $id = null;

        if($object instanceof Pago) $id = $object->getSolicitud()->getId();
        elseif($object instanceof Solicitud) $id = $object->getId();

        $institucion = $this->institucionRepository->getInstitucionBySolicitudId($id);
        return $institucion->getNombre();
    }
}

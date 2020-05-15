<?php

namespace AppBundle\Repository;

use AppBundle\Entity\EstatusCampoInterface;
use Doctrine\ORM\EntityRepository;

class EstatusCampoRepository extends EntityRepository implements EstatusCampoRepositoryInterface
{
    function getEstatusPagado()
    {
        return $this->getEstatusByNombre(EstatusCampoInterface::PAGO);
    }

    private function getEstatusByNombre($nombre)
    {
        return $this->findOneBy(['nombre' =>  $nombre]);
    }
}

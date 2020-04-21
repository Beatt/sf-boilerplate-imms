<?php

namespace AppBundle\Repository;

use Carbon\Carbon;
use Doctrine\ORM\EntityRepository;

class AgreementRepository  extends EntityRepository
{
    public function getAgreementsGreaterThanOneYear()
    {
        return $this->createQueryBuilder('agreement')
            ->where('agreement.vigencia > :greater')
            ->setParameter('greater', Carbon::now()->addMonths(12))
            ->getQuery()
            ->getResult();
    }

    public function testAgreementsLessThanOneYearAndGreaterThanSixMonths()
    {
        return $this->createQueryBuilder('agreement')
            ->where('agreement.vigencia > :greater AND agreement.vigencia <= :less')
            ->setParameter('greater', Carbon::now()->addMonths(6))
            ->setParameter('less', Carbon::now()->addMonths(12))
            ->getQuery()
            ->getResult();
    }

    public function testAgreementsLessThanSixMonths()
    {
        return $this->createQueryBuilder('agreement')
            ->where('agreement.vigencia <= :less')
            ->setParameter('less', Carbon::now()->addMonths(6))
            ->getQuery()
            ->getResult();
    }
}

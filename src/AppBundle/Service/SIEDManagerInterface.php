<?php


namespace AppBundle\Service;

use AppBundle\DTO\Sied;

interface SIEDManagerInterface
{
  /**
   * @param string $matricula
   * @param string $claveDelegacional
   * @return Sied|null
   */
  public function getDataFromSIEDByMatriculaYClaveDelegacional($matricula, $claveDelegacional);
}
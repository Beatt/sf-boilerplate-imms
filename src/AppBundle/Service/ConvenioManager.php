<?php

namespace AppBundle\Service;

use DateTime;
use AppBundle\Entity\Carrera;
use AppBundle\Entity\CicloAcademico;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\Delegacion;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\NivelAcademico;
use Doctrine\ORM\EntityManagerInterface;

class ConvenioManager implements ConvenioManagerInterface
{

  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function processDataCSV($data)
    {
      $conv = new Convenio();
      $conv->setNombre( @$data['﻿nombre'] ?: '');
      $conv->setNumero( @$data['numero'] ?: '');
      $conv->setSector(@$data['sector'] );
      $conv->setTipo( @$data['tipo'] );
      if (@$data['vigencia']) {
        try{
          $formats = ["Y-m-d", "Y/m/d", "d-m-Y", "d/m/Y"];
          foreach ($formats as $format) {
            $d = DateTime::createFromFormat($format, $data['vigencia']);
            if ($d && $d->format($format) === $data['vigencia']) {
              $conv->setVigencia($d);
              break;
            }
          }
        } catch(\Exception $e) { }
      }
      $conv->setInstitucion(
        $this->entityManager->getRepository(Institucion::class)
          ->findOneByNombre(@$data['institucion'] )
      );
      $conv->setDelegacion(
        $this->entityManager->getRepository(Delegacion::class)
          ->findOneByNombre(mb_strtoupper(@$data['delegacion']))
      );
      $conv->setCicloAcademico(
        $this->entityManager->getRepository(CicloAcademico::class)
          ->findOneByNombre(@$data['ciclo'] )
      );

      $conv->setCarrera(
        $this->entityManager->getRepository(Carrera::class)
          ->findOneByNombre( mb_strtoupper(@$data['carrera']))
      );

      return $conv;
    }
}

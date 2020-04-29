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
      $conv->setNombre( @$data['﻿nombre']);
      $conv->setSector(@$data['sector'] );
      $conv->setTipo( @$data['tipo'] );
      if (@$data['vigencia']) {
        try{
          $conv->setVigencia(new DateTime($data['vigencia']));
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
/*      if (@$data['grado']) {
        $grado = $this->entityManager->getRepository(NivelAcademico::class)
          ->findOneByNombre($data['grado']);
        $conv->setGradoAcademico($grado);
        $conv->setCarrera(
        $this->entityManager->getRepository(Carrera::class)
          ->findOneBy(
            array("nombre" => mb_strtoupper(@$data['carrera']),
              "nivelAcademico" => $grado )
            )
        );
      }*/

      return $conv;
    }
}

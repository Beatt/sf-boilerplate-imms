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
    $keys = array_keys($data);
    $newData = [];
    foreach ($keys as $key) {
      $newKey = trim(mb_strtolower($key));
      $newKey = str_replace(['á','é','í','ó','ú'],['a','e','i','o','u'], $newKey);
      $newData[$newKey] = trim(($data[$key]));
    }
    $data = $newData;

    $conv = new Convenio();
    if (array_key_exists('nombre', $data))
      $conv->setNombre($data['nombre']);
    if (array_key_exists("numero", $data))
      $conv->setNumero($data['numero']);

    foreach (['sector', 'tipo'] as $field) {
      if (array_key_exists($field, $data)) {
        $valores = $field === 'sector' ? Convenio::SECTORES
          : ($field === 'tipo' ? Convenio::TIPOS : []);
        foreach ($valores as $f) {
          $comp = str_replace( ['á','é','í','ó','ú'],['a','e','i','o','u'],
            trim(mb_strtolower($f)));
          $input = str_replace( ['á','é','í','ó','ú'],['a','e','i','o','u'],
            mb_strtolower($data[$field]));
          if ($input == $comp) {
            switch ($field) {
              case 'sector':
                $conv->setSector($f);
                break;
              case 'tipo':
                $conv->setTipo($f);
                break;
            }
            break;
          }
        }
      }
    }

    if (array_key_exists('vigencia', $data)) {
      try {
        $formats = ["Y-m-d", "Y/m/d", "d-m-Y", "d/m/Y"];
        foreach ($formats as $format) {
          $d = DateTime::createFromFormat($format, $data['vigencia']);
          if ($d && $d->format($format) === $data['vigencia']) {
            $conv->setVigencia($d);
            break;
          }
        }
      } catch (\Exception $e) {
      }
    }
    if (array_key_exists('institucion', $data))
      $conv->setInstitucion(
        $this->entityManager->getRepository(Institucion::class)
          ->findOneByNombre($data['institucion'])
      );
    if (array_key_exists('delegacion', $data))
      $conv->setDelegacion(
        $this->entityManager->getRepository(Delegacion::class)
          ->searchOneByNombre($data['delegacion'])
      );
    if (array_key_exists('ciclo', $data))
      $conv->setCicloAcademico(
        $this->entityManager->getRepository(CicloAcademico::class)
          ->searchOneByNombre($data['ciclo'])
      );
    if (array_key_exists('carrera', $data)
      && array_key_exists('grado', $data)) {
      $conv->setCarrera(
        $this->entityManager->getRepository(Carrera::class)
          ->searchOneByNombreGrado($data['carrera'], $data['grado'])
      );
    }

    return $conv;
  }
}

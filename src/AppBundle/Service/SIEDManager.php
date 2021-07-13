<?php

namespace AppBundle\Service;

use AppBundle\DTO\Sied;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SIEDManager implements SIEDManagerInterface
{
  /** @var string */
  private $siedURL;

  /** @var LoggerInterface */
  private $logger;



  public function __construct( $siedURL, LoggerInterface $logger)
  {
    $this->siedURL = $siedURL;
    $this->logger = $logger;
  }

  /**
   * @param string $matricula
   * @param string $claveDelegacional
   * @return Sied|null
   */
  public function getDataFromSIEDByMatriculaYClaveDelegacional($matricula, $claveDelegacional)
  {
      $this->logger->info('SOLICITANDO RECURSOS A SIED...', ['class' => SIEDManager::class]);

        $body = [
          'Delegacion' => $claveDelegacional,
          'Matricula' => $matricula,
          'RFC' => '',
        ];

        $response = Request::create($this->siedURL, 'POST', ['body' => $body]);

        try {
          $xmlResult = simplexml_load_string($response->getContent());
        } catch (\Exception $exception) {
          $this->logger->critical('¡Hubo un problema a la hora de solicitar el recurso!', [
            'class' => SIEDManager::class,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'body' => $body,
          ]);

          throw new Exception('¡Hubo un problema a la hora de solicitar el recurso!');

          //throw new TimeoutException('¡Hubo un problema a la hora de solicitar el recurso!');
        }

        $jsonDecodeResult = json_encode($xmlResult);
        if (!$jsonDecodeResult) {
          $this->logger->critical('¡Hubo un problema al convertir el texto a json!', [
            'class' => SIEDManager::class,
            'value' => $xmlResult,
          ]);
        }

        $usuario = json_decode($jsonDecodeResult, true);
        if (is_null($usuario)) {
          $this->logger->critical('¡Hubo un problema al convertir el json a array!', [
            'class' => SIEDManager::class,
            'value' => $jsonDecodeResult,
          ]);
        }

        $this->logger->info('FINALIZO LA SOLICITUD DE RECURSOS A SIED...', ['class' => SIEDManager::class]);

        if (isset($usuario['qry']['ERROR'])) {
          //throw new NotFoundHttpException('El recurso no se ha encontrado');
          throw  new NotFoundHttpException('No se encontró el recurso');
        }

        return $this->makeSiedFromArray($usuario);
  }

  /**
   * @param array $usuario
   * @return Sied
   */
  private function makeSiedFromArray($usuarioSiap)
  {
    $sied = new Sied();

    $sied->nombre = $usuarioSiap['EMPLEADOS']['NOMBRE'];
    $sied->apaterno = $usuarioSiap['EMPLEADOS']['APE_PATERNO'];
    $sied->amaterno = $usuarioSiap['EMPLEADOS']['APE_MATERNO'];
    $sied->curp = $usuarioSiap['EMPLEADOS']['EMP_RECURP'];
    $sied->rfc = $usuarioSiap['EMPLEADOS']['RFC'];
    $sied->sexo = $usuarioSiap['EMPLEADOS']['SEXO'];
    $sied->fechaIngreso = $usuarioSiap['EMPLEADOS']['FECHAINGRESO'];
    $sied->correoInstitucional = $usuarioSiap['EMPLEADOS']['FECHAINGRESO'];
    $sied->unidad = $usuarioSiap['EMPLEADOS']['DELEGACION'];
    $sied->antiguedad = $usuarioSiap['EMPLEADOS']['ANTIGUEDAD'];
    $sied->adscripcion = $usuarioSiap['EMPLEADOS']['ADSCRIPCION'];
    $sied->nombreCategoria = $usuarioSiap['EMPLEADOS']['PUE_DESPUE'];
    $sied->claveCategoria = $usuarioSiap['EMPLEADOS']['EMP_KEYPUE'];

    return $sied;
  }

}
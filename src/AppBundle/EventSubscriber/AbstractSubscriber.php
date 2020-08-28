<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Usuario;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/*
* Class AbstractSubscriber
* @package AppBundle\EventSubscriber
*/
abstract class AbstractSubscriber
{
  /**
  * Default action for logs
  */
  const UNKNOWN_ACTION = 'unknown_action';

  /**
  * @var ContainerInterface
  */
  protected $container;

  /**
  * AbstractSubscriber constructor.
  * @param ContainerInterface $container
  */
  public function __construct(ContainerInterface $container,
                              RequestStack $request)
  {
    $this->container = $container;
    $this->request = $request;
  }

  /**
  * @param string $action
  * @param array $context
  */
  protected function logDB($action = self::UNKNOWN_ACTION,
                               array $context) {
  $this->container->get('monolog.logger.db')->info($action, $context);
  }

}
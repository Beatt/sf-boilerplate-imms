<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

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
   * @var Request
   */
  protected $request;

  /**
  * AbstractSubscriber constructor.
  * @param ContainerInterface $container
  */
  public function __construct(ContainerInterface $container,
                              RequestStack $request)
  {
    $this->container = $container;
    $this->request = $request->getCurrentRequest();
  }

  /**
  * @param string $action
  * @param array $context
  */
  protected function logDB($action = self::UNKNOWN_ACTION,
                               array $context=[], $type='info') {
    switch ($type) {
      case 'error':
        $this->container->get('monolog.logger.db')->error($action, $context);
        break;
      case 'info':
      default:
        $this->container->get('monolog.logger.db')->info($action, $context);


    }
  }

}
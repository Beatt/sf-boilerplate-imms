<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
* @package App\EventSubscriber
*/
class SecuritySubscriber implements EventSubscriberInterface
{
  /**
   * @param EntityManagerInterface $em
   * @param LoggerInterface $logger
   * @param ContainerInterface $container
   * @param RequestStack $request
   */
  public function __construct(EntityManagerInterface $em,
                              LoggerInterface $logger,
                              ContainerInterface $container,
                              RequestStack $request)
  {
    $this->em = $em;
    $this->logger = $logger;
    $this->container = $container;
    $this->request = $request;
  }

  /**
  * @return array
  */
  public static function getSubscribedEvents()
  {
    return [
      SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin'
    ];
  }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
      $user = $event->getAuthenticationToken()->getUser();

      if ($user instanceof  Usuario ) {
        $this->container->get('monolog.logger.db')->info(
          'Inicio de Sesión de Usuario',
          $this->processRequest()
        );
      }
    }

  /**
   * @param array $record
   * @return array
   */
  protected function processRequest()
  {
    $record = [];
    $req = $this->request->getCurrentRequest();
    $record['client_ip']       = $req->getClientIp();
    //$record['client_port']     = $req->getPort();
    $record['uri']             = $req->getUri();
    $record['query_string']    = $req->getQueryString();
    //$record['method']          = $req->getMethod();
    $record['request']['username']         = $req->request->get('_username');

    return $record;
  }

  }
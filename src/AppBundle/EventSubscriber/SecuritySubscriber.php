<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Usuario;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
* @package App\EventSubscriber
*/
class SecuritySubscriber  extends AbstractSubscriber implements EventSubscriberInterface
{

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

      $permisos = $user->getPermisos();
      $req = $this->request;
      $record = [];
      $record['request']['_username']  = $req->request->get('_username');
      if ($permisos)
        $record['permiso'] = $permisos[0]->getNombre();

      $this->logDB('Inicio de Sesión de Usuario', $record);
    }

  }
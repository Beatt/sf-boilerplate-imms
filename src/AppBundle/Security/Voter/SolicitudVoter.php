<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Usuario;
use AppBundle\ObjectValues\SolicitudId;
use AppBundle\ObjectValues\UsuarioId;
use AppBundle\Repository\GetExistSolicitud\GetExistSolicitud;
use AppBundle\Repository\GetExistSolicitud\Solicitud as GetExistSolicitudResult;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class SolicitudVoter extends Voter
{
    const DETALLE_DE_SOLICITUD = 'detalle_de_solicitud';
    const REGISTRAR_MONTOS = 'registrar_montos';
    const CORREGIR_MONTOS = 'corregir_montos';
    const SELECCIONAR_FORMA_DE_PAGO = 'seleccionar_forma_de_pago';

    private $getExistSolicitud;

    public function __construct(GetExistSolicitud $getExistSolicitud)
    {
        $this->getExistSolicitud = $getExistSolicitud;
    }

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, $this->getViewsAllowed())) return false;
        if(!$subject instanceof Solicitud) return false;

        return true;
    }

    /**
     * @param $attribute
     * @param Solicitud $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Usuario $user */
        $user = $token->getUser();

        /** @var GetExistSolicitudResult $solicitud */
        $solicitud = $this->getExistSolicitud->ofUsuario(
            SolicitudId::fromString($subject->getId()),
            UsuarioId::fromString($user->getId())
        );

        if(!$solicitud->isSolicitudOfCurrentUser()) return false;

        return true;
    }

    /**
     * @return string[]
     */
    protected function getViewsAllowed()
    {
        return [
            self::DETALLE_DE_SOLICITUD,
            self::REGISTRAR_MONTOS,
            self::CORREGIR_MONTOS,
            self::SELECCIONAR_FORMA_DE_PAGO
        ];
    }
}

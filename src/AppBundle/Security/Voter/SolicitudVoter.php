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
use Symfony\Component\Security\Core\Security;

final class SolicitudVoter extends Voter
{
    const DETALLE_DE_SOLICITUD = 'detalle_de_solicitud';
    const REGISTRAR_MONTOS = 'registrar_montos';
    const CORREGIR_MONTOS = 'corregir_montos';
    const SELECCIONAR_FORMA_DE_PAGO = 'seleccionar_forma_de_pago';
    const DETALLE_DE_FORMA_DE_PAGO = 'detalle_de_forma_de_pago';
    const DESCARGAR_REFERENCIAS_BANCARIAS = 'descargar_referencias_bancarias';
    const CARGAR_COMPROBANTE = 'cargar_comprobante';
    const CORRECCION_DE_PAGO_FOFOE = 'correccion_de_pago_fofoe';

    private $getExistSolicitud;

    private $security;

    public function __construct(
        GetExistSolicitud $getExistSolicitud,
        Security $security
    ) {
        $this->getExistSolicitud = $getExistSolicitud;
        $this->security = $security;
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

        if(!$this->security->isGranted('ROLE_IE')) return false;

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
            self::SELECCIONAR_FORMA_DE_PAGO,
            self::DETALLE_DE_FORMA_DE_PAGO,
            self::DESCARGAR_REFERENCIAS_BANCARIAS,
            self::CARGAR_COMPROBANTE,
            self::CORRECCION_DE_PAGO_FOFOE,
        ];
    }
}

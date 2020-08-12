<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Pago;
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
    const DETALLE_DE_SOLICITUD_MULTIPLE = 'detalle_de_solicitud_multiple';
    const OBTENER_GESTION_DE_PAGOS = 'obtener_gestion_de_pagos';
    const CARGAR_COMPROBANTE_DE_PAGO = 'cargar_comprobante_de_pago';
    const DESCARGAR_COMPROBANTE_DE_PAGO = 'descargar_comprobante_de_pago';
    const DESCARGAR_FORMATOS_FOFOE = 'descargar_formatos_fofoe';

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
        if(!$subject instanceof Solicitud && !$subject instanceof Pago) return false;

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Usuario $user */
        $user = $token->getUser();

        if(!$this->security->isGranted('ROLE_IE')) return false;

        $solicitudId = null;
        if($subject instanceof Solicitud) $solicitudId = $subject->getId();
        elseif($subject instanceof Pago) $solicitudId = $subject->getSolicitud()->getId();

        /** @var GetExistSolicitudResult $solicitud */
        $solicitud = $this->getExistSolicitud->ofUsuario(
            SolicitudId::fromString($solicitudId),
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
            self::DETALLE_DE_SOLICITUD_MULTIPLE,
            self::OBTENER_GESTION_DE_PAGOS,
            self::CARGAR_COMPROBANTE_DE_PAGO,
            self::DESCARGAR_COMPROBANTE_DE_PAGO,
            self::DESCARGAR_FORMATOS_FOFOE
        ];
    }
}

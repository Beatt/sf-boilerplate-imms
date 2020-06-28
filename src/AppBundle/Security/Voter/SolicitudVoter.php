<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class SolicitudVoter extends Voter
{
    const DETALLE_DE_SOLICITUD = 'detalle_de_solicitud';

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::DETALLE_DE_SOLICITUD])) return false;
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



        return true;
    }
}

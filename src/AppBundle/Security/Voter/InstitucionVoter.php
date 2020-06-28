<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Institucion;
use AppBundle\Entity\Usuario;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class InstitucionVoter extends Voter
{
    const PERFIL = 'perfil';

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::PERFIL])) return false;
        if(!$subject instanceof Institucion) return false;

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Usuario $user */
        $user = $token->getUser();

        $this->logger->info('lol');

        if(!$user->getInstitucion()) {

            return false;
        }

        return true;
    }
}

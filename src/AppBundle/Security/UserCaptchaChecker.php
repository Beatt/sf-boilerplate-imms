<?php
namespace AppBundle\Security;

use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCaptchaChecker implements UserCheckerInterface
{
    private $session;
    private $requestStack;

    public function __construct(SessionInterface $session, RequestStack $requestStack)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof Usuario) {
            return;
        }

        $captcha = $this->requestStack->getMasterRequest()->get('captcha');

        if(empty($this->session->get('captcha_phrase'))) {
            throw new NotFoundHttpException();
        }

        if($this->session->get('captcha_phrase') !== $captcha) {
            throw new CustomUserMessageAuthenticationException(
                'El código no coincide con el que ingresaste. Por favor vuelve a intentarlo o genere uno nuevo.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Usuario) {
            return;
        }
    }
}

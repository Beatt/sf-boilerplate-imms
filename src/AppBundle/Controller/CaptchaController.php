<?php
namespace AppBundle\Controller;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class CaptchaController
{
    const LENGTH = 5;
    const PHRASE = 'abcdefghijklmnpqrstuvwxyz123456789';
    const MAX_FRONT_LINES = 0;
    const MAX_BEHIND_LINES = 0;
    const WIDTH = 180;
    const HEIGHT = 90;
    private $session;
    private $engine;

    public function __construct(SessionInterface $session, Environment $engine)
    {
        $this->session = $session;
        $this->engine = $engine;
    }

    /**
     * @Route("/generarCaptcha", name="generar_captcha")
     */
    public function indexAction()
    {
        $phrase = new PhraseBuilder(self::LENGTH, self::PHRASE);
        $builder = new CaptchaBuilder(null, $phrase);
        $builder->setMaxFrontLines(self::MAX_FRONT_LINES);
        $builder->setMaxBehindLines(self::MAX_BEHIND_LINES);
        $builder->build(self::WIDTH, self::HEIGHT);

        $this->session->set('captcha_phrase', $builder->getPhrase());

        return new Response($this->engine->render('captcha.html.twig', [
            'captcha' => $builder->inline()
        ]));
    }
}

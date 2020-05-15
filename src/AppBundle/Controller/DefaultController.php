<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Traductor;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if($request->get('changeLocale') !== null) {
            $request->setLocale($request->get('changeLocale'));
        }

        /** @var Traductor $translate */
        $translate = $this->getDoctrine()
            ->getRepository(Traductor::class)
            ->getTextsByLocale($request->getLocale());

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'translate' => $translate !== null ? $translate->getTraductorDTO() : null
        ]);
    }

    /**
     * @Route("/enviar-email", name="default#enviar-email")
     * @param Swift_Mailer $mailer
     * @return JsonResponse
     */
    public function enviarEmail(Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                    'envio_email_prueba.html.twig',
                    [
                        'name' => 'Gabriel'
                    ]
                ),
                'text/html'
            )
        ;

        $result = $mailer->send($message);

        return new JsonResponse(['status' => Response::HTTP_OK]);
    }
}

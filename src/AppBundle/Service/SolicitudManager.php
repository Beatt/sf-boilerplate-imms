<?php


namespace AppBundle\Service;


use AppBundle\Entity\Solicitud;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Swift_Mailer;

class SolicitudManager implements SolicitudManagerInterface
{

    private $entityManager;
    private $logger;
    private $mailer;
    private $templating;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger,
        Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->mailer     = $mailer;
        $this->templating = $templating;
    }

    public function update(Solicitud $solicitud)
    {
        $this->entityManager->persist($solicitud);
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        }
        return [
            'status' => true
        ];
    }

    public function create(Solicitud $solicitud)
    {
        $solicitud->setEstatus(Solicitud::CREADA);
        $solicitud->setFecha(Carbon::now());
        try {
            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
            $solicitud->setNoSolicitud("NS_" . str_pad($solicitud->getId(), 6, '0', STR_PAD_LEFT));
            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
        } catch (OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        }
        return [
            'status' => true,
            'message' => 'Solicitud almacenada con éxito',
            'object' => ['id' => $solicitud->getId(), 'fecha' => $solicitud->getFecha(), 'no_solicitud' => $solicitud->getNoSolicitud()]
        ];
    }

    public function finalizar(Solicitud $solicitud)
    {
        $solicitud->setEstatus(Solicitud::CONFIRMADA);
        try {
            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
        } catch (OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        }
        return [
            'status' => true
        ];
    }

    public function validarMontos(Solicitud $solicitud, $montos = [], $is_valid = false)
    {
        $solicitud->setValidado($is_valid);
        if($is_valid){
            $solicitud->setEstatus(Solicitud::MONTOS_VALIDADOS_CAME);
        }else{
            $solicitud->setEstatus(Solicitud::MONTOS_INCORRECTOS_CAME);
            $this->sendEmailMontosInvalidos($solicitud);
        }
        try {
            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
            if($solicitud->getValidado()){
                foreach ($montos as $monto) {
                    if($monto->getMontoInscripcion() && $monto->getMontoColegiatura()){
                        $this->entityManager->persist($monto);
                        $this->entityManager->flush();
                    }else{
                        throw new \Exception("Montos no puedes ser vacios");
                    }
                }
            }

        } catch (OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        }
        return [
            'status' => true
        ];
    }

    public function sendEmailMontosInvalidos(Solicitud $solicitud)
    {
        $message = (new \Swift_Message('Los montos de la solicitud ' . $solicitud->getNoSolicitud() . ' son invalidos'))
            ->setFrom('send@example.com') //cambiar el destinatario XD
            ->setTo($solicitud->getInstitucion()->getCorreo() ? $solicitud->getInstitucion()->getCorreo() : 'recipient@example.com' )
            ->setBody(
                $this->templating->render('emails/came/montos_invalidos.html.twig',['solicitud' => $solicitud]),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}
<?php


namespace AppBundle\Service;


use AppBundle\Entity\Institucion;
use AppBundle\Entity\Permiso;
use AppBundle\Entity\Rol;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Usuario;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Swift_Mailer;

class SolicitudManager implements SolicitudManagerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $templating;
    private $sender;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger,
        Swift_Mailer $mailer, \Twig_Environment $templating, EncoderFactoryInterface $encoderFactory,
        $sender
    )
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->mailer     = $mailer;
        $this->templating = $templating;
        $this->encoderFactory = $encoderFactory;
        $this->sender = $sender;
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

    public function finalizar(Solicitud $solicitud, Usuario $came_user = null)
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

        $this->generateUser($solicitud, $came_user);

        return [
            'status' => true
        ];
    }

    public function validarMontos(Solicitud $solicitud, $montos = [], $is_valid = false, Usuario $came_usuario = null)
    {
        $solicitud->setValidado($is_valid);

        if($is_valid){
            $solicitud->setEstatus(Solicitud::MONTOS_VALIDADOS_CAME);
        }else{
            $solicitud->setEstatus(Solicitud::MONTOS_INCORRECTOS_CAME);
            $this->sendEmailMontosInvalidos($solicitud);
        }
        try {
            if ($is_valid) {
                $solicitud->setEstatus(Solicitud::MONTOS_VALIDADOS_CAME);
                $this->processMontos($solicitud);
            } else {
                $solicitud->setEstatus(Solicitud::MONTOS_INCORRECTOS_CAME);
                $this->sendEmailMontosInvalidos($solicitud, $came_usuario);
            }

            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
            if ($solicitud->getValidado()) {
                foreach ($montos as $monto) {
                    if ($monto->getMontoInscripcion() && $monto->getMontoColegiatura()) {
                        $this->entityManager->persist($monto);
                        $this->entityManager->flush();
                    } else {
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

    public function sendEmailMontosInvalidos(Solicitud $solicitud, Usuario $came_usuario)
    {
        $message = (new \Swift_Message('Los montos de la solicitud ' . $solicitud->getNoSolicitud() . ' son invalidos'))
            ->setFrom($this->sender)
            ->setTo($solicitud->getInstitucion()->getCorreo() ? $solicitud->getInstitucion()->getCorreo() : 'recipient@example.com' )
            ->setBody(
                $this->templating->render('emails/came/montos_invalidos.html.twig',['solicitud' => $solicitud, 'came' => $came_usuario]),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    public function generateUser(Solicitud $solicitud, Usuario $came_usuario = null)
    {
        $institucion = $solicitud->getInstitucion();
        $nueva_password = substr(md5(mt_rand()), 0, 8);

        $ie_permiso = $this->entityManager->getRepository(Permiso::class)->findOneBy(['clave' => 'IE']);

        $user_db = $this->entityManager->getRepository(Usuario::class)->findOneBy(['correo' => $institucion->getCorreo()]);
        if(!$user_db){
            $user = new Usuario();
            $user->setCorreo($institucion->getCorreo());
            $user->setNombre(substr($institucion->getRepresentante(), 0, 50));
            $user->setApellidoPaterno(substr($institucion->getNombre(), 0, 50));
            $user->setCurp('0');
            $user->setRfc('0');
            $user->setSexo('0');
            $user->setFechaIngreso(Carbon::now());
            $user->setRegims(0);
            $user->setContrasena($this->encoderFactory->getEncoder($user)->encodePassword($nueva_password, $user->getSalt()));
        }else{
            $user = $user_db;
            $nueva_password = '';
        }

        $user->setActivo(true);
        if(!$user->getPermisos()->contains($ie_permiso)){
            $user->addPermiso($ie_permiso);
        }

        $this->entityManager->persist($user);
        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $institucion->setUsuario($user);
        $this->entityManager->persist($institucion);
        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $this->sendEmailBienvenida($solicitud, $nueva_password, $came_usuario);
    }


    private function sendEmailBienvenida(Solicitud $solicitud,  $password,  Usuario $came_usuario = null)
    {
        $message = (new \Swift_Message('Sistema de Administración del FOFOE'))
            ->setFrom($this->sender)
            ->setTo($solicitud->getInstitucion()->getCorreo() ? $solicitud->getInstitucion()->getCorreo() : 'recipient@example.com' )
            ->setBody(
                $this->templating->render('emails/came/institucion_bienvenida.html.twig',['solicitud' => $solicitud, 'password' => $password, 'came' => $came_usuario]),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    private function processMontos(Solicitud $solicitud)
    {
        $monto_solicitud = 0;
        foreach ($solicitud->getCampoClinicos() as $campoClinico) {
            $total_campo = 0;
            if($campoClinico->getConvenio()->getCicloAcademico()->getId() === 1){
                $total_campo = $campoClinico->getSubTotal() * $campoClinico->getWeeks();
            }else{
                $total_campo = $campoClinico->getSubTotal();
            }
            $campoClinico->setMonto($total_campo);
            $monto_solicitud+=$total_campo;
            $this->entityManager->persist($campoClinico);
        }
        $solicitud->setMonto($monto_solicitud);
        $this->entityManager->persist($solicitud);
        $this->entityManager->flush();
    }
}

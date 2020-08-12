<?php

namespace AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\Permiso;
use AppBundle\Entity\Usuario;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class GeneradorFormatoFofoe implements GeneradorFormatoFofoeInterface
{
    const PDF_NAME = 'fofoe.pdf';

    private $pdf;

    private $templating;

    private $entityManager;

    private $tokenStorage;

    public function __construct(
        EntityManagerInterface $entityManager,
        Pdf $pdf,
        Environment $templating,
        TokenStorageInterface  $tokenStorage
    ) {
        $this->pdf = $pdf;
        $this->templating = $templating;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function responsePdf($path, CampoClinico $campoClinico, $overwrite = false)
    {
        $came = $this->entityManager->getRepository(Usuario::class)->getCamebyDelegacion($campoClinico->getSolicitud()->getDelegacion()->getId());
        $file = "$path/{$campoClinico->getSolicitud()->getNoSolicitud()}/cc_{$campoClinico->getId()}/".self::PDF_NAME;
        if (!file_exists($file) || $overwrite) {
            $this->pdf->generateFromHtml(
                $this->templating->render(
                    'formatos/fofoe.html.twig',
                    [
                        'campo_clinico' => $campoClinico,
                        'came' => $came
                    ]
                ),
                $file,
                ['page-size' => 'Letter','encoding' => 'utf-8'],
                $overwrite
            );
            try{
                $user = $this->tokenStorage->getToken()->getUser();
                $permiso_came = $this->entityManager->getRepository(Permiso::class)->findOneBy(['clave' => 'CAME']);
                if($user && !$user->getPermisos()->contains($permiso_came)){
                    //2 es el estado de pendiente de pago
                    if(is_null($campoClinico->getEstatus()) || $campoClinico->getEstatus()->getId() === 1){
                        $estatus = $this->entityManager->getRepository(EstatusCampo::class)->find(2);
                        $campoClinico->setEstatus($estatus);
                        $this->entityManager->persist($campoClinico);
                        $this->entityManager->flush();
                    }
                }
            }catch (\Exception $ex){}
        }

        return $file;
    }

    public function getFileName(CampoClinico $campoClinico)
    {
        $date = Carbon::now()->format('Ymd');
        $type = $campoClinico->getConvenio()->getCicloAcademico()->getId()  === 1? 'CCS' : 'INT';

        return "{$date}_{$campoClinico->getSolicitud()->getNoSolicitud()}-{$type}_{$campoClinico->getId()}_FormatoFOFOE.pdf" . '"';
    }
}

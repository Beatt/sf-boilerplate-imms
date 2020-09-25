<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class GeneradorCredenciales implements GeneradorCredencialesInterface
{
    const PDF_NAME = 'credenciales.pdf';
    /**
     * @var Pdf
     */
    private $pdf;
    /**
     * @var Environment
     */
    private $templating;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        Pdf $pdf,
        Environment $templating
    )
    {
        $this->entityManager = $entityManager;
        $this->pdf = $pdf;
        $this->templating = $templating;
    }

    public function responsePdf($path, CampoClinico $campoClinico, $overwrite = false)
    {
        $file = "$path/{$campoClinico->getSolicitud()->getNoSolicitud()}/cc_{$campoClinico->getId()}/".self::PDF_NAME;
        if (!file_exists($file) || $overwrite) {
            $this->pdf->generateFromHtml(
                $this->templating->render(
                    'formatos/credenciales.html.twig',
                    [
                        'campo_clinico' => $campoClinico,
                        'total' => $campoClinico->getLugaresAutorizados(),
                    ]
                ),
                $file,
                ['page-size' => 'Letter','encoding' => 'utf-8'],
                $overwrite
            );
            try{
                //7 es el estado de contraseñas generados
                if(is_null($campoClinico->getEstatus()) || $campoClinico->getEstatus()->getId() !== 7){
                    $estatus = $this->entityManager->getRepository(EstatusCampo::class)->find(7);
                    $campoClinico->setEstatus($estatus);
                    $this->entityManager->persist($campoClinico);
                    $this->entityManager->flush();
                }
            }catch (\Exception $ex){}
        }

        $response = new Response(file_get_contents($file));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . self::PDF_NAME . '"');
        $response->headers->set('Content-length', filesize($file));
        unlink($file);
        return $response;
    }
}
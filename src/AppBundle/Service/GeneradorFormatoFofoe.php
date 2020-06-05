<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class GeneradorFormatoFofoe implements GeneradorFormatoFofoeInterface
{

    const PDF_NAME = 'fofoe.pdf';
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
        $this->pdf = $pdf;
        $this->templating = $templating;
        $this->entityManager = $entityManager;
    }

    public function responsePdf($path, CampoClinico $campoClinico, Usuario $came = null, $overwrite = false)
    {
        $file = "$path/{$campoClinico->getSolicitud()->getNoSolicitud()}/cc_{$campoClinico->getId()}/".self::PDF_NAME;
        if (!file_exists($file) || $overwrite) {
            $this->pdf->generateFromHtml(
                $this->templating->render(
                    'formatos/fofoe.html.twig',
                    ['campo_clinico' => $campoClinico, 'came' => $came]
                ),
                $file,
                ['page-size' => 'Letter','encoding' => 'utf-8'],
                $overwrite
            );
            try{
                //2 es el estado de pendiente de pago
                if(is_null($campoClinico->getEstatus()) || $campoClinico->getEstatus()->getId() === 1){
                    $estatus = $this->entityManager->getRepository(EstatusCampo::class)->find(2);
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
        return $response;
    }
}
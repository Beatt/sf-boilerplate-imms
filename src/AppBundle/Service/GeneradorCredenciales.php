<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;
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

    public function __construct(
        Pdf $pdf,
        Environment $templating
    )
    {
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
                    ['campo_clinico' => $campoClinico, 'total' => $campoClinico->getLugaresAutorizados()]
                ),
                $file,
                ['page-size' => 'Letter','encoding' => 'utf-8'],
                $overwrite
            );
        }

        $response = new Response(file_get_contents($file));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . self::PDF_NAME . '"');
        $response->headers->set('Content-length', filesize($file));
        return $response;
    }
}
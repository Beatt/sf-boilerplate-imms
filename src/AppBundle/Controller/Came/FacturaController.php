<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\Factura;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FacturaController extends Controller
{
    /**
     * @Route("/factura/{factura_id}/download", methods={"GET"}, name="factura.download")
     * @param $factura_id
     * @return mixed
     */
    public function downloadFileAction($factura_id)
    {
        $factura = $this->getDoctrine()
            ->getRepository(Factura::class)
            ->find($factura_id);

        if (!$factura) {
            throw $this->createNotFoundException(
                'Not found for id ' . $factura_id
            );
        }
        $downloadHandler = $this->get('vich_uploader.download_handler');
        return $downloadHandler->downloadObject($factura, 'zipFile');
    }
}

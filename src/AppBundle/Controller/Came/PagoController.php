<?php


namespace AppBundle\Controller\Came;


use AppBundle\Entity\Pago;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class PagoController extends Controller
{
    /**
     * @Route("/pago/{pago_id}/download", methods={"GET"}, name="pago.comprobante")
     * @param $pago_id
     * @return mixed
     */
    public function downloadComprobanteAction($pago_id)
    {
        $pago = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->find($pago_id);

        if (!$pago) {
            throw $this->createNotFoundException(
                'Not found for id ' . $pago_id
            );
        }
        $downloadHandler = $this->get('vich_uploader.download_handler');
        return $downloadHandler->downloadObject($pago, 'comprobantePagoFile');
    }
}

<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Carrera;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\MontoCarrera;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Service\SolicitudManagerInterface;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Handler\DownloadHandler;

class DummyController extends \AppBundle\Controller\DIEControllerController
{

    /**
     * @Route("/came/dummy/store-oficio/{id}", methods={"GET"}, name="came.dummy.store.oficio")
     * @param Request $request
     * @param $id
     */
    public function setOficioDummyAction(Request $request, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        $path = __DIR__.'/../../../../tests/AppBundle/Service/';

        copy($path.'pdf.pdf', $path. 'pdf-test.pdf');

        $pdf_url = $path.'pdf-test.pdf';

        $file = new UploadedFile(
            $pdf_url,
            'mydocument.pdf',
            'application/pdf',
            filesize($pdf_url),
            null,
            true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
        );

        $solicitud->setUrlArchivoFile($file);

        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'status'=> true,
            'pdf_url' => $pdf_url,
            'pdf_exists' => file_exists($pdf_url),
            'solicitud' => $this->get('serializer')->normalize(
                $solicitud, 'json', ['attributes' => ['id', 'urlArchivo', 'urlArchivoFile']]
            )
        ]);
    }


    /**
     * @Route("/came/dummy/oficio/{id}", methods={"GET"}, name="came.dummy.index.oficio")
     * @param Request $request
     * @param $id
     */
    public function getOficioAction(Request $request, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }        $downloadHandler = $this->get('vich_uploader.download_handler');
        return $downloadHandler->downloadObject($solicitud, 'urlArchivoFile');
    }

    /**
     * @Route("/came/dummy/solicitud/{solicitud_id}/terminar", methods={"GET"}, name="came.dummy.solicitar.terminar")
     * @param SolicitudManagerInterface $solicitudManager
     * @param $solicitud_id
     */
    public function dummySolicitudTerminar(SolicitudManagerInterface $solicitudManager, $solicitud_id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($solicitud_id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $solicitud_id
            );
        }

        $solicitudManager->finalizar($solicitud, $this->getUser());
        return $this->jsonResponse(['status' => true]);
    }

    /**
    * @Route("/came/dummy/show_roles", methods={"GET"})
    */
    public function dummyShowRolesAction()
    {
        $user = $this->getUser();
        return $this->json([
            'roles' => $this->get('serializer')->normalize(
                $user->getRols(), 'json', ['attributes' => ['id', 'nombre']]
            ),
            'permisos' => $this->get('serializer')->normalize(
                $user->getRoles(), 'json', ['attributes' => ['id', 'nombre']]
            )
        ]);
    }

    /**
     * @Route("/came/dummy/solicitud/{solicitud_id}/registro_montos", methods={"GET"}, name="came.dummy.solicitar.registro_montos")
     * @param Request $request
     * @param $solicitud_id
     */
    public function registroMontosAction(Request $request, $solicitud_id)
    {
        /* @var Solicitud $solicitud */
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($solicitud_id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $solicitud_id
            );
        }

        $carreras = $this->getDoctrine()
            ->getRepository(Carrera::class)
            ->getDistinctCarrerasBySolicitud($solicitud_id);
        foreach ($carreras as $carrera){
            $monto = new MontoCarrera();
            $monto->setCarrera($carrera);
            $monto->setSolicitud($solicitud);
            $monto->setMontoColegiatura(rand(1000, 10000));
            $monto->setMontoInscripcion(rand(1000, 3000));
            $this->getDoctrine()->getManager()->persist($monto);
        }
        $path = __DIR__.'/../../../../tests/AppBundle/Service/';

        copy($path.'pdf.pdf', $path. 'pdf-test.pdf');

        $pdf_url = $path.'pdf-test.pdf';

        $file = new UploadedFile(
            $pdf_url,
            'mydocument.pdf',
            'application/pdf',
            filesize($pdf_url),
            null,
            true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
        );
        $solicitud->setEstatus(SolicitudInterface::EN_VALIDACION_DE_MONTOS_CAME);
        $solicitud->setUrlArchivoFile($file);
        $solicitud->setConfirmacionOficioAdjunto(true);
        $this->getDoctrine()->getManager()->persist($solicitud);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(['status' => true]);
    }

    /**
     * @Route("/came/dummy/solicitud/{solicitud_id}/registro_pagos", methods={"GET"}, name="came.dummy.solicitar.registro_pagos")
     * @param Request $request
     * @param $solicitud_id
     */
    public function registroPagosAction(Request $request, $solicitud_id)
    {
        /* @var Solicitud $solicitud */
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($solicitud_id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $solicitud_id
            );
        }

        $path = __DIR__.'/../../../../tests/AppBundle/Service/';
        copy($path.'pdf.pdf', $path. 'pdf-test.pdf');
        $pdf_url = $path.'pdf-test.pdf';
        $file = new UploadedFile(
            $pdf_url,
            'mydocument.pdf',
            'application/pdf',
            filesize($pdf_url),
            null,
            true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
        );

        // 3 es pago
        /* @var EstatusCampo $cc_status */
        $cc_status = $this->getDoctrine()->getManager()->getRepository(EstatusCampo::class)->find(3);
        $solicitud->setEstatus(Solicitud::EN_VALIDACION_FOFOE);
        $solicitud->setTipoPago(Solicitud::TIPO_PAGO_UNICO);
        /* @var CampoClinico $campoClinico */
        foreach ($solicitud->getCampoClinicos() as $campoClinico){
            $campoClinico->setEstatus($cc_status);
            $this->getDoctrine()->getManager()->persist($campoClinico);
        }
        /* @var Pago $pago */
        $pago = new Pago();
        $pago->setSolicitud($solicitud);
        $pago->setMonto($solicitud->getMonto());
        $pago->setFechaPago(Carbon::now());
        $referencia = substr(md5(mt_rand()), 0, 10);
        $pago->setReferenciaBancaria($referencia);
        $pago->setRequiereFactura(true);
        $pago->setComprobantePagoFile($file);
        $this->getDoctrine()->getManager()->persist($pago);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(['status' => true, 'pago' => $pago->getId()]);
    }

    /**
     * @Route("/came/dummy/test", methods={"GET"})
     */
    public function testAction(){
        $sender = $this->getParameter('mailer_sender');
        return new JsonResponse([
            'sender' => $sender
        ]);
    }

    /**
     * @Route("/came/dummy/credentials/{campo_clinico_id}", methods={"GET"})
     * @param $campo_clinico_id
     */
    public function credentialsJsAction(Request $request, $campo_clinico_id)
    {
        $request->headers->add(array('X-Requested-With' => 'XMLHttpRequest'));
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($campo_clinico_id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id ' . $campo_clinico
            );
        }
        if(!$this->validarSolicitudDelegacion($campo_clinico->getSolicitud())){
            $this->addFlash('danger', 'No puedes ver una solicitud de otra delegación');
            return $this->redirectToRoute('came.solicitud.index');
        }
        return  $this->render('formatos/credenciales_dummy.html.twig', ['campo_clinico' => $campo_clinico, 'total' => $campo_clinico->getLugaresAutorizados()]);

    }
}

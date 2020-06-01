<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\Solicitud;
use AppBundle\Service\SolicitudManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        }
        $downloadHandler = $this->get('vich_uploader.download_handler');
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

        $solicitudManager->finalizar($solicitud);
        return $this->jsonResponse(['status' => true]);
    }
}
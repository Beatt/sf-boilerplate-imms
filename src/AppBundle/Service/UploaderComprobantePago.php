<?php

namespace AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Repository\PagoRepositoryInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderComprobantePago implements UploaderComprobantePagoInterface
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    public function __construct(PagoRepositoryInterface $pagoRepository)
    {
        $this->pagoRepository = $pagoRepository;
    }

    /**
     * @param CampoClinico $campoClinico
     * @param UploadedFile $file
     * @throws Exception
     */
    public function update(CampoClinico $campoClinico, UploadedFile $file)
    {
        /** @var Pago $pago */
        $pago = $this->pagoRepository->getComprobante($campoClinico->getReferenciaBancaria());
        if($pago === null) throw new Exception('El campo clinico no tiene un pago asociado');

        $pago->setComprobantePagoFile($file);

        $this->pagoRepository->save($pago);
    }
}

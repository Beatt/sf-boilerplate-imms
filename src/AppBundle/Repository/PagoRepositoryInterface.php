<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Doctrine\Common\Persistence\ObjectRepository;

interface PagoRepositoryInterface extends ObjectRepository
{
    function getAllPagosByRequest($id);
    public function getComprobante($referenciaBancaria);
    public function save(Pago $pago);

    public function getComprobantesPagoByReferenciaBancaria($referenciaBancaria);

    public function getReporteIngresosMes($anio);

    public function getAllPagosByInstitucion($id);

    public function getComprobantesPagoValidadosByReferenciaBancaria($referenciaBancaria);

    public function paginate($perPage = 10, $offset = 1, $filters = []);
}

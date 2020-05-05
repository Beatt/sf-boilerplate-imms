<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\NivelAcademico;
use AppBundle\Entity\Solicitud;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Serializer;

class SolicitudRepositoryTest extends WebTestCase
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Serializer */
    private $serializer;

    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->serializer = $container->get('serializer');

        parent::setUp();
    }

    public function testGetSolicitudesConEstatusActualEnConfirmado()
    {
        $solicitud = $this->createSolicitudByEstatus(
            Solicitud::CONFIRMADA
        );

        $this->entityManager->flush();

        $solicitudesNormalize = $this->getSolicitudNormalized([$solicitud]);
        $this->assertEquals(Solicitud::CONFIRMADA, $solicitudesNormalize[0]['estatusActual']);

    }

    public function testGetSolicitudesConPagoUnico()
    {
        $estatusCampo = $this->createEstatusCampo();

        $campoClinico = $this->createCampoClinico();
        $campoClinico->setEstatus($estatusCampo);

        $solicitud = $this->createSolicitudByEstatus(
            Solicitud::EN_VALIDACION_DE_MONTOS,
            Solicitud::TIPO_PAGO_UNICO
        );
        $solicitud->addCamposClinico($campoClinico);

        $this->entityManager->flush();

        $solicitudesNormalized = $this->getSolicitudNormalized([$solicitud]);

        $this->assertEquals(
            EstatusCampo::MONTOS_VALIDADOS,
            $solicitudesNormalized[0]['estatusActual']
        );
    }

    public function testGetSolicitudesConPagoIndividual()
    {
        $nivelAcademico = new NivelAcademico();
        $nivelAcademico->setNombre('dummydata');

        $estatusCampo = new EstatusCampo();
        $estatusCampo->setNombre('dummydata');
        $estatusCampo->setEstatus(EstatusCampo::MONTOS_VALIDADOS);

        $estatusCampoPendienteCFDI = new EstatusCampo();
        $estatusCampoPendienteCFDI->setNombre('dummydata');
        $estatusCampoPendienteCFDI->setEstatus(EstatusCampo::PENDIENTE_CFDI_POR_FOFOE);

        $campoClinico1 = $this->createCampoClinico();
        $campoClinico2 = $this->createCampoClinico();
        $campoClinico1->setEstatus($estatusCampo);
        $campoClinico2->setEstatus($estatusCampoPendienteCFDI);

        $solicitud = $this->createSolicitudByEstatus(
            Solicitud::EN_VALIDACION_DE_MONTOS,
            Solicitud::TIPO_PAGO_INDIVIDUAL
        );
        $solicitud->addCamposClinico($campoClinico1);
        $solicitud->addCamposClinico($campoClinico2);

        $this->entityManager->persist($nivelAcademico);
        $this->entityManager->persist($estatusCampo);
        $this->entityManager->persist($estatusCampoPendienteCFDI);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(CampoClinico::class);

        $camposClinicos = $repository->createQueryBuilder('campo_clinico')
            ->leftJoin('campo_clinico.solicitud', 'solicitud')
            ->where('campo_clinico.solicitud = :id')
            ->setParameter('id', $solicitud->getId())
            ->orderBy('campo_clinico.estatus', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        $solicitudesNormalized = $this->getSolicitudNormalized($camposClinicos);

        $this->assertCount(2, $solicitudesNormalized);

        $this->assertEquals(
            EstatusCampo::PENDIENTE_CFDI_POR_FOFOE,
            $solicitudesNormalized[0]['estatusActual']
        );
        $this->assertEquals(
            EstatusCampo::MONTOS_VALIDADOS,
            $solicitudesNormalized[1]['estatusActual']
        );
    }

    /**
     * @param $estatus
     * @param string $tipoPago
     * @return Solicitud
     */
    private function createSolicitudByEstatus($estatus, $tipoPago = Solicitud::TIPO_PAGO_UNICO) {
        $solicitud = new Solicitud();
        $solicitud->setTipoPago($tipoPago);
        $solicitud->setFecha(Carbon::now());
        $solicitud->setEstatus($estatus);
        $solicitud->setReferenciaBancaria('dummydata');

        $this->entityManager->persist($solicitud);

        return $solicitud;
    }

    /**
     * @return CampoClinico
     */
    private function createCampoClinico()
    {
        $campoClinico = new CampoClinico();
        $campoClinico->setLugaresAutorizados(0);
        $campoClinico->setLugaresSolicitados(0);
        $campoClinico->setHorario(Carbon::now());
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(6));
        $campoClinico->setPromocion('dummydata');
        $campoClinico->setReferenciaBancaria('dummydata');
        $campoClinico->setMonto(00000);

        $this->entityManager->persist($campoClinico);

        return $campoClinico;
    }

    /**
     * @param array $solicitudes
     * @return array
     */
    private function getSolicitudNormalized(array $solicitudes)
    {
        return $this->serializer
            ->normalize(
                $solicitudes,
                'json',
                [
                    'attributes' => [
                        'id',
                        'noSolicitud',
                        'fecha',
                        'estatusActual',
                        'noCamposSolicitados',
                        'noCamposAutorizados'
                    ]
                ]
            );
    }

    public function tearDown()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $purger = new ORMPurger($doctrine->getManager());
        $purger->purge();

    }

    /**
     * @return EstatusCampo
     */
    private function createEstatusCampo()
    {
        $estatusCampo = new EstatusCampo();
        $estatusCampo->setNombre('dummydata');
        $estatusCampo->setEstatus(EstatusCampo::MONTOS_VALIDADOS);

        $this->entityManager->persist($estatusCampo);

        return $estatusCampo;
    }
}

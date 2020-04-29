<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SolicitudRepositoryTest extends WebTestCase
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $purger = new ORMPurger($doctrine->getManager());
        $purger->purge();

        $entityManager = $container->get('doctrine.orm.entity_manager');
        $this->entityManager = $entityManager;
    }

    public function testGetSolicitudesConEstatusActualEnConfirmado()
    {
        $institucion = new Institucion();
        $institucion->setNombre('dummydata');
        $institucion->setRepresentante('dummydata');
        $institucion->setDireccion('dummydata');
        $institucion->setCedulaIdentificacion('dummydata');
        $institucion->setSitioWeb('dummydata');
        $institucion->setCorreo('dummydata');
        $institucion->setTelefono('dummydata');
        $institucion->setRfc('dummydata');

        $convenio = new Convenio();
        $convenio->setNombre('dummydata');
        $convenio->setInstitucion($institucion);
        $convenio->setSector('dummydata');
        $convenio->setVigencia(Carbon::now()->addMonths(6));
        $convenio->setTipo('dummydata');

        $campoClinico = $this->createCampoClinicoConEstatus();
        $campoClinico->setConvenio($convenio);

        $solicitud = $this->createSolicitudByEstatus(
            $campoClinico,
            Solicitud::CONFIRMADA
        );

        $this->entityManager->persist($institucion);
        $this->entityManager->persist($convenio);
        $this->entityManager->persist($campoClinico);
        $this->entityManager->persist($solicitud);
        $this->entityManager->flush();

        /** @var SolicitudRepositoryInterface $repository */
        $repository = $this->entityManager->getRepository(Solicitud::class);

        /** @var Paginator $paginator */
        $paginator = $repository->getAllSolicitudesByInstitucion(
            $institucion->getId(),
            Solicitud::TIPO_PAGO_UNICO,
            null,
            1,
            null
        );

        $solicitudes = $paginator->getQuery()->getResult();
        dump(count($solicitudes));

        /** @var Solicitud $solicitude */
        foreach ($solicitudes as $solicitude) {
            dump('verga');
        }
    }

    public function testGetSolicitudesConPagoUnico()
    {
        $estatusCampo = new EstatusCampo();
        $estatusCampo->setNombre('dummydata');
        $estatusCampo->setEstatus(EstatusCampo::MONTOS_VALIDADOS);

        $campoClinico = $this->createCampoClinicoConEstatus($estatusCampo);

        $solicitud = $this->createSolicitudByEstatus(
            $campoClinico,
            Solicitud::EN_VALIDACION_DE_MONTOS
        );

        $this->entityManager->persist($estatusCampo);
        $this->entityManager->persist($campoClinico);
        $this->entityManager->persist($solicitud);
        $this->entityManager->flush();

        $solicitudes = $this->entityManager->getRepository(Solicitud::class)->findAll();

        /** @var Solicitud $solicitud */
        $solicitud = $solicitudes[0];

        $this->assertEquals(
            'montos_validados',
            $solicitud->getEstatusActual()
        );
    }

    public function testGetSolicitudesConPagoIndividual()
    {
        $estatusCampo = new EstatusCampo();
        $estatusCampo->setNombre('dummydata');
        $estatusCampo->setEstatus(EstatusCampo::MONTOS_VALIDADOS);

        $estatusCampoPendienteCFDI = new EstatusCampo();
        $estatusCampoPendienteCFDI->setNombre('dummydata');
        $estatusCampoPendienteCFDI->setEstatus(EstatusCampo::PENDIENTE_CFDI_POR_FOFOE);

        $campoClinico1 = $this->createCampoClinicoConEstatus($estatusCampo);
        $campoClinico2 = $this->createCampoClinicoConEstatus($estatusCampoPendienteCFDI);

        $solicitud = $this->createSolicitudByEstatus(
            $campoClinico1,
            Solicitud::EN_VALIDACION_DE_MONTOS
        );
        $solicitud->addCamposClinico($campoClinico2);

        $this->entityManager->persist($estatusCampo);
        $this->entityManager->persist($estatusCampoPendienteCFDI);
        $this->entityManager->persist($campoClinico1);
        $this->entityManager->persist($campoClinico2);
        $this->entityManager->persist($solicitud);
        $this->entityManager->flush();

        /** @var SolicitudRepositoryInterface $solicitudes */
        $repository = $this->entityManager->getRepository(Solicitud::class);

        $solicitudes = $repository->getAllSolicitudesByInstitucion(
        );

        $this->assertCount(2, $solicitudes);

        /** @var Solicitud $solicitud */
        $solicitud = $solicitudes[0];

        $this->assertEquals(
            'montos_validados',
            $solicitud->getEstatusActual()
        );
    }

    /**
     * @param CampoClinico $campoClinico
     * @param $estatus
     * @return Solicitud
     */
    private function createSolicitudByEstatus(
        CampoClinico $campoClinico,
        $estatus
    ) {
        $solicitud = new Solicitud();
        $solicitud->setTipoPago(Solicitud::TIPO_PAGO_UNICO);
        $solicitud->setFecha(Carbon::now());
        $solicitud->setEstatus($estatus);
        $solicitud->setReferenciaBancaria('dummydata');
        $solicitud->addCamposClinico($campoClinico);
        return $solicitud;
    }

    /**
     * @param EstatusCampo $estatusCampo
     * @return CampoClinico
     */
    private function createCampoClinicoConEstatus(EstatusCampo $estatusCampo = null)
    {
        $campoClinico = new CampoClinico();
        $campoClinico->setLugaresAutorizados(10);
        $campoClinico->setLugaresSolicitados(10);
        $campoClinico->setHorario(Carbon::now());
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(6));
        $campoClinico->setPromocion('dummydata');
        $campoClinico->setReferenciaBancaria('dummydata');
        $campoClinico->setMonto(00000);
        $campoClinico->setEstatus($estatusCampo);

        return $campoClinico;
    }
}

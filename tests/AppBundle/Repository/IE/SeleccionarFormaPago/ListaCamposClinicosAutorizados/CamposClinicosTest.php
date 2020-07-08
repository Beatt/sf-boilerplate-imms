<?php

namespace Tests\AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados;

use AppBundle\Entity\Solicitud;
use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados\CamposClinicos;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Tests\AppBundle\AbstractWebTestCase;

final class CamposClinicosTest extends AbstractWebTestCase
{
    /**
     * @var CamposClinicos
     */
    private $camposClinicos;

    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    /**
     * @var NormalizerInterface
     */
    private $serializer;

    public function setUp()
    {
        parent::setUp();
        $this->camposClinicos = $this->container->get(CamposClinicos::class);
        $this->solicitudRepository = $this->container->get(SolicitudRepositoryInterface::class);
        $this->serializer = $this->container->get('serializer');
    }

    public function testObtenerCamposClinicosAutorizados()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([]);

        $camposClinicos = $this->camposClinicos->listaCamposClinicosAutorizados(
            SolicitudId::fromString($solicitud->getId())
        );

        $camposClinicosNormalizers = $this->serializer->normalize(
            $camposClinicos,
            'json'
        );

        foreach($camposClinicosNormalizers as $campoClinico) {
            $this->assertNotNull($campoClinico['id']);
            $this->assertNotNull($campoClinico['unidad']['nombre']);
            $this->assertNotNull($campoClinico['convenio']['carrera']['nombre']);
            $this->assertNotNull($campoClinico['convenio']['carrera']['nivelAcademico']['nombre']);
            $this->assertNotNull($campoClinico['convenio']['cicloAcademico']['nombre']);
            $this->assertNotNull($campoClinico['lugaresSolicitados']);
            $this->assertNotNull($campoClinico['lugaresAutorizados']);
            $this->assertNotNull($campoClinico['fechaInicial']);
            $this->assertNotNull($campoClinico['fechaFinal']);
            $this->assertNotNull($campoClinico['numeroSemanas']);
            $this->assertNotNull($campoClinico['montoPagar']);
            $this->assertNotEmpty($campoClinico['enlaceCalculoCuotas']);
        }
    }
}

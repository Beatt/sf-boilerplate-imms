<?php

namespace Tests\AppBundle\Normalizer;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Normalizer\FormaPagoNormalizer;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormaPagoNormalizerTest extends WebTestCase
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $this->normalizer = $container->get('serializer');
        $this->solicitudRepository = $container->get(SolicitudRepositoryInterface::class);
    }

    public function testMostrarTablaConCamposClinicosAprobados()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::CONFIRMADA
        ]);

        $formaPagoNormalizer = new FormaPagoNormalizer($this->normalizer);
        $campoClinicoNormalize = $formaPagoNormalizer->normalizeCamposClinicos($solicitud->getCamposClinicos());

        foreach($campoClinicoNormalize as $campoClinico) {
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

    public function testMostrarTablaConCamposClinicosNoAprobados()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::CONFIRMADA
        ]);

        $formaPagoNormalizer = new FormaPagoNormalizer($this->normalizer);
        /** @var CampoClinico $campoClinico */
        $campoClinico = $solicitud->getCamposClinicos()->first();
        $campoClinico->setLugaresAutorizados(0);
        $campoClinicoNormalize = $formaPagoNormalizer->normalizeCamposClinicos($solicitud->getCamposClinicos());

        foreach($campoClinicoNormalize as $campoClinico) {
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
            $this->assertEquals('No aplica', $campoClinico['montoPagar']);
            $this->assertEmpty($campoClinico['enlaceCalculoCuotas']);
        }
    }
}

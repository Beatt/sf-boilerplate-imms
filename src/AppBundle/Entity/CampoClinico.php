<?php

namespace AppBundle\Entity;

use AppBundle\Repository\PagoRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="campo_clinico")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CampoClinicoRepository")
 */
class CampoClinico implements ReferenciaBancariaInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaInicial;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaFinal;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $horario;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $promocion;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value = 0)
     */
    private $lugaresSolicitados;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $lugaresAutorizados;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Convenio")
     * @ORM\JoinColumn(name="convenio_id", referencedColumnName="id")
     */
    private $convenio;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $referenciaBancaria;

    /**
     * @ORM\Column(type="float", precision=24, scale=4, nullable=true)
     */
    private $monto;

    /**
     * @var Solicitud
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Solicitud", inversedBy="camposClinicos")
     * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
     */
    private $solicitud;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EstatusCampo", cascade={"persist"})
     * @ORM\JoinColumn(name="estatus_campo_id", referencedColumnName="id")
     */
    private $estatus;

    /**
     * @var Unidad
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unidad", inversedBy="camposClinicos")
     * @ORM\JoinColumn(name="unidad_id", referencedColumnName="id")
     */
    private $unidad;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $asignatura;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param DateTime $fechaInicial
     * @return CampoClinico
     */
    public function setFechaInicial($fechaInicial)
    {
        $this->fechaInicial = $fechaInicial;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    public function getDisplayFechaInicial() {
      return $this->fechaInicial->format('d/m/Y');
    }

    /**
     * @param DateTime $fechaFinal
     * @return CampoClinico
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

  public function getDisplayFechaFinal() {
    return $this->fechaFinal->format('d/m/Y');
  }

    /**
     * @param string $horario
     * @return CampoClinico
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * @param string $promocion
     * @return CampoClinico
     */
    public function setPromocion($promocion)
    {
        $this->promocion = $promocion;

        return $this;
    }

    /**
     * @return string
     */
    public function getPromocion()
    {
        return $this->promocion;
    }

    /**
     * @param integer $lugaresSolicitados
     * @return CampoClinico
     */
    public function setLugaresSolicitados($lugaresSolicitados)
    {
        $this->lugaresSolicitados = $lugaresSolicitados;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLugaresSolicitados()
    {
        return $this->lugaresSolicitados;
    }

    /**
     * @param integer $lugaresAutorizados
     * @return CampoClinico
     */
    public function setLugaresAutorizados($lugaresAutorizados)
    {
        $this->lugaresAutorizados = $lugaresAutorizados;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLugaresAutorizados()
    {
        return $this->lugaresAutorizados;
    }

    /**
     * @param string $referenciaBancaria
     * @return CampoClinico
     */
    public function setReferenciaBancaria($referenciaBancaria)
    {
        $this->referenciaBancaria = $referenciaBancaria;

        return $this;
    }

    /**
     * @return string
     */
    public function getReferenciaBancaria()
    {
        return $this->referenciaBancaria;
    }

    /**
     * @param float $monto
     * @return CampoClinico
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * @return float
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @param Convenio $convenio
     * @return CampoClinico
     */
    public function setConvenio(Convenio $convenio = null)
    {
        $this->convenio = $convenio;

        return $this;
    }

    /**
     * @return Convenio
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    public function getNombreCicloAcademico() {
      return $this->convenio ?
        $this->convenio->getCicloAcademico()->getNombre() : "";
    }

    public function getDisplayCarrera() {
      $carrera = $this->convenio ?
        $this->convenio->getCarrera() : null;

      return $carrera ?
        $carrera->getDisplayName() : "";
    }

    /**
     * @param Solicitud $solicitud
     * @return CampoClinico
     */
    public function setSolicitud(Solicitud $solicitud = null)
    {
        $this->solicitud = $solicitud;

        return $this;
    }

    /**
     * @return Solicitud
     */
    public function getSolicitud()
    {
        return $this->solicitud;
    }

    /**
     * @param EstatusCampo $estatus
     * @return void
     */
    public function setEstatus(EstatusCampo $estatus = null)
    {
        $this->estatus = $estatus;
    }

    /**
     * @param Unidad $unidad
     * @return CampoClinico
     */
    public function setUnidad(Unidad $unidad = null)
    {
        $this->unidad = $unidad;
        return $this;
    }

    /**
     * @return EstatusCampo
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
      * @return Unidad
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    public function getFactura()
    {
        $criteria = PagoRepository::createGetPagoByReferenciaBancariaCriteria($this->getReferenciaBancaria());

        /** @var Pago $pago */
        $pago = $this->getSolicitud()->getPagos()->matching($criteria)->first();

        if(!$pago->isRequiereFactura()) return 'No solicitada';

        if($this->getEstatus()->getNombre() !== EstatusCampoInterface::CREDENCIALES_GENERADAS) return 'Pendiente';

        return $pago->getFactura()->getZip();
    }

    public function getComprobante()
    {
        $criteria = PagoRepository::createGetPagoByReferenciaBancariaCriteria($this->getReferenciaBancaria());

        /** @var Pago $pago */
        $pago = $this->getSolicitud()->getPagos()->matching($criteria)->first();

        return sprintf('/uploads/files/institucion/pagos/%s', $pago->getComprobantePago());
    }

    public function getWeeks()
    {
        $inicial = Carbon::instance($this->fechaInicial);
        $final = Carbon::instance($this->fechaFinal);
        return $final->diffInWeeks($inicial) > 0 ? $final->diffInWeeks($inicial) : 1;
    }


    /**
     * @param string $asignatura
     * @return CampoClinico
     */
    public function setAsignatura($asignatura)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * @return string
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    public function getNumeroSemanas()
    {
        return Carbon::instance($this->fechaInicial)->diffInWeeks(
            Carbon::instance($this->fechaFinal)
        );
    }

    public function getEnlaceCalculoCuotas()
    {
        return $this->lugaresAutorizados !== 0 ?
            sprintf('/formato/campo_clinico/%s/formato_fofoe/download', $this->id) :
            '';
    }

    public function getMontoPagar()
    {
        return $this->lugaresAutorizados !== 0 ?
            $this->monto :
            'No aplica';
    }

    /**
     * @return string
     */
    public function getFechaInicialFormatted()
    {
        return $this->getFechaInicial()->format('d/m/Y');
    }

    /**
     * @return string
     */
    public function getFechaFinalFormatted()
    {
        return $this->getFechaFinal()->format('d/m/Y');
    }

    /**
     * @return float|null
     */
    public function getMontoInscripcion()
    {
        $result = null;
        $montos = $this->getSolicitud()->getMontosCarreras();
        if($montos){
            foreach ($montos as $monto){
                if($monto->getCarrera()->getId() === $this->getConvenio()->getCarrera()->getId()){
                    $result = $monto->getMontoInscripcion();
                }
            }
        }
        return $result;
    }

    /**
     * @return float|null
     */
    public function getMontoColegiatura()
    {
        $result = null;
        $montos = $this->getSolicitud()->getMontosCarreras();
        if($montos){
            foreach ($montos as $monto){
                if($monto->getCarrera()->getId() === $this->getConvenio()->getCarrera()->getId()){
                    $result = $monto->getMontoColegiatura();
                }
            }
        }
        return $result;
    }

    /**
     * @return float|int
     */
    public function getImporteColegiaturaAnualIntegrada()
    {
        return $this->getMontoColegiatura() + $this->getMontoInscripcion();
    }

    /**
     * @return float
     */
    public function getFactorSemanalAutorizado()
    {
        return .005;
    }

    /**
     * @return float|int
     */
    public function getImporteAlumno()
    {
        if($this->getConvenio()->getCicloAcademico()->getId() === 1){
            return $this->getImporteColegiaturaAnualIntegrada() * $this->getFactorSemanalAutorizado();
        }
        return $this->getImporteColegiaturaAnualIntegrada() * .50;
    }

    /**
     * @return float|int
     */
    public function getSubTotal()
    {
        return $this->getImporteAlumno() * $this->getLugaresAutorizados();
    }

    public function getPago()
    {
        return $this->getPagos()->first();
    }

    public function getPagos()
    {
        $criteria = PagoRepository::createGetPagoByReferenciaBancariaCriteria($this->getReferenciaBancaria());
        return $this->getSolicitud()->getPagos()->matching($criteria);
    }

    public function getDisplayDelegacion() {
      return $this->convenio ?
        $this->convenio
          ->getDelegacion()
          ->getNombre()
        : '';
    }

    public function getDisplayCicloAcademico() {
      return $this->convenio ?
        $this->convenio
          ->getCicloAcademico()
          ->getNombre()
        : '';
    }

}

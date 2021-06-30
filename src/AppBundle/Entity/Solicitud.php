<?php

namespace AppBundle\Entity;

use AppBundle\Repository\CampoClinicoRepository;
use AppBundle\Repository\PagoRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Exception;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Table(name="solicitud")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SolicitudRepository")
 * @Vich\Uploadable
 */
class Solicitud implements SolicitudInterface, SolicitudTipoPagoInterface, ReferenciaBancariaInterface
{

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="no_solicitud", type="string", length=9, unique=true, nullable=true)
     */
    protected $noSolicitud;

    /**
     * @ORM\Column(type="date")
     */
    protected $fecha;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $estatus;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $referenciaBancaria;

    /**
     * @ORM\Column(type="float", precision=24, scale=4, nullable=true)
     */
    protected $monto;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CampoClinico", mappedBy="solicitud")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $camposClinicos;

    protected $montosCarreras;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $tipoPago;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $documento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $urlArchivo;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="comprobantes_inscripcion", fileNameProperty="urlArchivo")
     * @Assert\File(
     *  maxSize="1000000",
     *  mimeTypes = {"application/pdf", "application/x-pdf"},
     * )
     */
    protected $urlArchivoFile;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $validado;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fechaComprobante;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $observaciones;

    /**
     * @var Pago
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Pago", mappedBy="solicitud", cascade={"persist"})
     * @ORM\OrderBy({"id" = "ASC"})
    */
    protected $pagos;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $confirmacionOficioAdjunto;

    public function __construct()
    {
        $this->fecha = new \DateTime();
        $this->camposClinicos = new ArrayCollection();
        $this->pagos = new ArrayCollection();
        $this->montosCarreras = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $noSolicitud
     * @return Solicitud
     */
    public function setNoSolicitud($noSolicitud)
    {
        $this->noSolicitud = $noSolicitud;

        return $this;
    }

    /**
     * @return string
     */
    public function getNoSolicitud()
    {
        return $this->noSolicitud;
    }

    /**
     * @param \DateTime $fecha
     * @return Solicitud
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha->format('d/m/Y');
    }

    /**
     * @param string $estatus
     * @return Solicitud
     */
    public function setEstatus($estatus)
    {
        $allowedStatus = [
            self::CREADA,
            self::CONFIRMADA,
            self::EN_VALIDACION_DE_MONTOS_CAME,
            self::MONTOS_INCORRECTOS_CAME,
            self::MONTOS_VALIDADOS_CAME,
            self::FORMATOS_DE_PAGO_GENERADOS,
            self::CARGANDO_COMPROBANTES,
            self::EN_VALIDACION_FOFOE,
            self::CREDENCIALES_GENERADAS
        ];

        if(!in_array($estatus, $allowedStatus)) {
            throw new \InvalidArgumentException(sprintf(
                'El estatus %s no se puede asignar, selecciona una de las opciones validas %s',
                $estatus,
                implode(', ', $allowedStatus)
            ));
        }

        $this->estatus = $estatus;

        return $this;
    }


    /**
     * @return string
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * @param string $referenciaBancaria
     * @return Solicitud
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
     * @param string $documento
     * @return Solicitud
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param string $url_archivo
     * @return Solicitud
     */
    public function setUrlArchivo($url_archivo)
    {
        $this->urlArchivo = $url_archivo;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlArchivo()
    {
        return $this->urlArchivo;
    }


    /**
     * @param string $observaciones
     * @return Solicitud
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * @param boolean $validado
     * @return Solicitud
     */
    public function setValidado($validado)
    {
        $this->validado = $validado;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getValidado()
    {
        return $this->validado;
    }

    /**
     * @param DateTime $fecha_comprobante
     * @return Solicitud
     */
    public function setFechaComprobante($fecha_comprobante)
    {
        $this->fechaComprobante = $fecha_comprobante;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFechaComprobante()
    {
        return $this->fechaComprobante;
    }

    /**
     * @return string
     */
    public function getFechaComprobanteFormatted()
    {
        return $this->getFechaComprobante()? $this->getFechaComprobante()->format('d-m-Y') : '';
    }

    /**
     * @param float $monto
     * @return Solicitud
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
     * @return int
     * @throws Exception
     */
    public function getDemo()
    {
        return random_int(10, 10000);
    }

    public function getCampoClinicos()
    {
        return $this->camposClinicos;
    }

    public function getCamposClinicosSolicitados()
    {
        return count($this->getCampoClinicos());
    }

    /**
     * @return int
     */
    public function getCamposClinicosAutorizados()
    {
        $acc = 0;
        foreach ($this->getCampoClinicos() as $campoClinico) {
            if ($campoClinico->getLugaresAutorizados() > 0) {
                $acc++;
            }
        }
        return $acc;
    }

    /**
     * @return string
     */
    public function getEstatusCameFormatted()
    {
        $result = '';
        switch ($this->getEstatus()) {
            case self::CREADA :
                $result = 'En edición';
                break;
            case self::CONFIRMADA:
                $result = 'Solicitud Registrada';
                break;
            case self::EN_VALIDACION_DE_MONTOS_CAME:
                $result = 'Falta validar montos';
                break;
            case self::MONTOS_INCORRECTOS_CAME:
                $result = 'En corrección por IE';
                break;
            case self::MONTOS_VALIDADOS_CAME:
                $result = 'Validados';
                break;
            case self::FORMATOS_DE_PAGO_GENERADOS:
            case self::CARGANDO_COMPROBANTES:
            case self::EN_VALIDACION_FOFOE:
                $result = 'En proceso de pago';
                break;
            case self::CREDENCIALES_GENERADAS:
                $result = 'Descargar credenciales';
                break;
        }
        return $result;
    }

  public function getEstatusIEFormatted()
  {
    $result = '';
    switch ($this->getEstatus()) {
      case self::EN_VALIDACION_DE_MONTOS_CAME:
        $result = 'En validación de montos';
        break;
      case self::MONTOS_INCORRECTOS_CAME:
        $result = 'Montos incorrectos';
        break;
      case self::MONTOS_VALIDADOS_CAME:
        $result = 'Montos validados';
        break;
      default:
        $result = $this->getEstatus();
        break;
    }
    return $result;
  }

    /**
     * @return string
     */
    public function getEstatusFofoeFormatted()
    {
        return ''; //TODO
    }

    public function getInstitucion()
    {
        $result = null;
        $campos_clinicos = $this->getCampoClinicos();
        if ($campos_clinicos->count() > 0) {
            $result = $campos_clinicos[0]->getConvenio()->getInstitucion();
        }
        return $result;
    }

    /**
     * @return integer
     */
    public function getNoCamposSolicitados()
    {
        return $this->camposClinicos->count();
    }

    /**
     * @return integer
     */
    public function getNoCamposAutorizados()
    {
        /** @var CampoClinico $campoClinico */
        $noCamposSolicitados = array_filter($this->getCampoClinicos()->toArray(), function (CampoClinico $campoClinico) {
            return $campoClinico->getLugaresAutorizados() !== 0;
        });

        return count($noCamposSolicitados);
    }

    /**
     * @return string
     */
    public function getTipoPago()
    {
        return $this->tipoPago !== null ? $this->tipoPago : self::TIPO_PAGO_NULL;
    }

    /**
     * @param string $tipoPago
     */
    public function setTipoPago($tipoPago)
    {
        $this->tipoPago = $tipoPago;
    }

    /**
     * @param CampoClinico $camposClinico
     * @return Solicitud
     */
    public function addCamposClinico(CampoClinico $camposClinico)
    {
        if (!$this->camposClinicos->contains($camposClinico)) {
            $this->camposClinicos[] = $camposClinico;
            $camposClinico->setSolicitud($this);
        }

        return $this;
    }

    /**
     * @param CampoClinico $camposClinico
     */
    public function removeCamposClinico(CampoClinico $camposClinico)
    {
        $this->camposClinicos->removeElement($camposClinico);
    }

    /**
     * @return Collection
     */
    public function getCamposClinicos()
    {
        return $this->camposClinicos;
    }

    public function __toString()
    {
        return '' . $this->getNoSolicitud();
    }

    public function getPagosIndividuales()
    {
        $result = false;
        foreach ($this->getCampoClinicos() as $cc) {
            if ($cc->getReferenciaBancaria()) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    private function esSolicitudConfirmada()
    {
        return $this->estatus === Solicitud::CONFIRMADA;
    }

    /**
     * @param Pago $pago
     * @return Solicitud
     */
    public function addPago(Pago $pago)
    {
        $this->pagos[] = $pago;

        return $this;
    }

    /**
     * @param Pago $pago
     */
    public function removePago(Pago $pago)
    {
        $this->pagos->removeElement($pago);
    }

    /**
     * @return Collection
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    public function isPagoUnico()
    {
        return $this->getTipoPago() === SolicitudTipoPagoInterface::TIPO_PAGO_UNICO;
    }

    /**
     * @param MontoCarrera $montosCarrera
     * @return Solicitud
     */
    public function addMontosCarrera(MontoCarrera $montosCarrera)
    {
        if(!$this->montosCarreras->contains($montosCarrera)) {
            $this->montosCarreras[] = $montosCarrera;
            $montosCarrera->setSolicitud($this);
        }

        return $this;
    }

    /**
     * @param MontoCarrera $montosCarrera
     */
    public function removeMontosCarrera(MontoCarrera $montosCarrera)
    {
        if($this->montosCarreras->contains($montosCarrera)) {
            $this->montosCarreras->removeElement($montosCarrera);
        }
    }

    /**
     * @return Collection
     */
/*    public function getMontosCarreras()
    {
        return $this->montosCarreras;
    } */

    /**
     * @return File
     */
    public function getUrlArchivoFile()
    {
        return $this->urlArchivoFile;
    }

    /**
     * @param File $urlArchivoFile
     */
    public function setUrlArchivoFile($urlArchivoFile = null)
    {
        $this->urlArchivoFile = $urlArchivoFile;

        $this->setFechaComprobante(Carbon::now());
    }

    /**
     * @return bool
     */
    public function getConfirmacionOficioAdjunto()
    {
        return $this->confirmacionOficioAdjunto;
    }

    /**
     * @param bool $confirmacionOficioAdjunto
     */
    public function setConfirmacionOficioAdjunto($confirmacionOficioAdjunto)
    {
        $this->confirmacionOficioAdjunto = $confirmacionOficioAdjunto;
    }

    /**
     * @return Pago|null
     */
    public function getPago()
    {
        $result = null;
        $pagos = $this->getPagos();
        if(count($pagos)>0) {
            $result = $pagos[0];
        }
        return $result;
    }

    /**
     * @return Delegacion|null
     */
    public function getDelegacion()
    {
        $result = null;
        $cc = $this->getCampoClinicos()->first();
        if($cc) {
            $result = $cc->getUnidad()->getDelegacion();
        }
        return $result;
    }

    /**
     * @return Unidad|null
     */
    public function getUnidad()
    {
      $result = null;
      /** @var CampoClinico $cc */
      $cc = $this->getCampoClinicos()->first();
      if($cc && $cc->getUnidad() && $cc->getUnidad()->getEsUmae()){
        $result = $cc->getUnidad();
      }
      return $result;
    }

    public function getCampoClinicoByReferenciaBancaria($referenciaBancaria)
    {
        $criteria = CampoClinicoRepository::getCampoClinicoByReferenciaBancaria($referenciaBancaria);
        return $this->getCamposClinicos()->matching($criteria)->first();
    }

    public function getPagosByReferenciaBancaria($referenciaBancaria)
    {
        $criteria = PagoRepository::getPagosCargadosByReferenciaBancaria($referenciaBancaria);
        return $this->getPagos()->matching($criteria);
    }

    public function getMontosCarreras() {
      $this->montosCarreras = new ArrayCollection();
      foreach ($this->camposClinicos as $campo ) {
        if ($campo->getMontoCarrera()) {
          $this->montosCarreras->add($campo->getMontoCarrera());
        }
      }
      return $this->montosCarreras;
    }

}

import * as React from 'react'
import ReactDOM from 'react-dom'
import {
  dateFormat,
  getSchemeAndHttpHost,
  moneyFormat
} from "../../utils";
import {TIPO_PAGO} from "../../constants";
import ExpedienteSolicitud from "./ExpedienteSolicitud";

  const AccionFofoe = ({pago}) => {
  if (!pago || !pago.comprobantePago) return null;
  const RegistroFactura  = () => (<a className="btn btn-default" href={`${getSchemeAndHttpHost()}/fofoe/pagos/${pago.id}/registrar-factura`}>Registrar Factura</a>);
  const ValidarPago = () => (<a className="btn btn-default" href={`${getSchemeAndHttpHost()}/fofoe/pagos/${pago.id}/validacion-de-pago`}>Validar Pago</a>);

  return (
    <div className="col-md-6">
    {
      pago.validado || pago.validado == null ?
        <strong>Acción </strong> : null
    }
    {
      (pago.validado && pago.requiereFactura && !pago.facturaGenerada) ?
      <RegistroFactura />
      : pago.validado == null ?
      <ValidarPago />
      : null
      }
    </div>
  )
}

const DatosSolicitud = ({solicitud, montoTotal, campos, pago}) => {

  console.log(solicitud);

  function isPagoMultiple() {
    return solicitud.tipoPago === TIPO_PAGO.MULTIPLE;
  }

  function getMontoTotalTitle() {
    return isPagoMultiple() ?
      'Monto total del campo clínico:'
      : 'Monto total de la solicitud:';
  }

  function getEstado() {
    return !isPagoMultiple() ?
      solicitud.estatus
      : (campos.length > 0 ?
        campos[0].estatus.nombre
        : '' )
  }

  return (
    <div className="col-md-12 mb-20">
      <div className="row">
        <div className="col-md-4">
          <p className='mb-5'><strong>Solicitud</strong></p>
          <p className='mb-5'>No. de Solicitud: <strong>{solicitud.noSolicitud}</strong></p>
          <p className='mb-5'>Fecha de registro: <strong>{solicitud.fecha}</strong></p>
          <p className='mb-5'>Tipo de pago: <strong>{solicitud.tipoPago}</strong></p>
          <p className='mb-20'>{getMontoTotalTitle()} <strong>{moneyFormat(montoTotal)}</strong></p>
        </div>
        <div className="col-md-4">
          <p className='mb-5'><strong>Institución</strong></p>
          <p className='mb-5'>Nombre: <strong><a href={`${getSchemeAndHttpHost()}/fofoe/detalle-ie/${solicitud.institucion.id}`}>{solicitud.institucion.nombre}</a></strong></p>
          <p className='mb-5'>RFC: <strong>{solicitud.institucion.rfc}</strong></p>
          <p className='mb-5'>OOAD: <strong>{solicitud.delegacion.nombre}</strong></p>
          {
            solicitud.unidad && solicitud.unidad.esUmae ?
              <p className='mb-5'>UMAE: <strong>{solicitud.unidad.nombre}</strong></p>
              : null
          }
        </div>
      </div>
      <div className="row">
        <div className="col-md-6 mt-10">
          <p>Referencia Bancaria: <strong>{pago.referenciaBancaria}</strong></p>
          <p><strong>Estado de la referencia:</strong> {getEstado()}</p>
        </div>
        <AccionFofoe pago={pago} />
      </div>
    </div>
  )
}

const DatosCampo = ({campo}) => {
  return (
    <div className="col-md-4" >
      <p className='mb-5'><strong>Campo clínico</strong></p>
      <p className='mb-5'>Sede: <strong>{campo.unidad.nombre}</strong></p>
      <p className='mb-5'>Carrera: <strong>{campo.displayCarrera}</strong></p>
      <p className='mb-5'>Período: <strong>{campo.displayFechaInicial} - {campo.displayFechaFinal}</strong></p>
    </div>
  )
}

const HistorialPagos = ({pagos}) => {
  return (
    <div className="col-md-12 mb-20">
      <table className='table table-condensed'>
        <thead>
        <tr>
          <th>Comprobante registrado</th>
          <th>Fecha</th>
          <th>Monto validado</th>
          <th>Observaciones</th>
        </tr>
        </thead>
        <tbody>
        {
          pagos.length !== 0 ?
            pagos.map((pago, index) =>
              <tr key={index}>
                <td>
                  {
                    pago.comprobantePago ?
                    <a href={`${getSchemeAndHttpHost()}/fofoe/pagos/${pago.id}/descargar-comprobante-de-pago`}
                       download>Descargar</a>
                    : 'Pendiente de cargar'
                  }
                </td>

                <td>{pago.fechaPagoFormatted}</td>
                <td>{ pago.validado != null ?  moneyFormat(pago.monto) : '-'}</td>
                <td>{ pago.validado != null ? pago.observaciones : 'Pendiente de validar'}</td>
              </tr>
            ) :
            <tr>
              <td
                className='text-center text-info'
                colSpan={4}
              >
                Aún no se ha registrado ningún comprobante de pago
              </td>
            </tr>
        }
        </tbody>
      </table>
    </div>
  )
}

const HistorialFacturas = ({facturas}) => {
  return (
    <div className="row">
      <div className="col-md-12">
        <p className="mt-10 mb-10"><strong>Facturas generadas</strong></p>
        <table className='table'>
          <thead className='headers'>
          <tr>
            <th>Fecha Facturación</th>
            <th>Monto Facturado</th>
            <th>Archivo Factura</th>
            <th>Folio Factura</th>
          </tr>
          </thead>
          <tbody>
          {
            facturas.length > 0 ?

              facturas.map((factura, index) =>
                  <tr key={index}>
                    <td>{dateFormat(factura.fechaFacturacion)}</td>
                    <td>{factura.monto}</td>
                    <td>{factura.zip && <a href={`${getSchemeAndHttpHost()}/fofoe/factura/${factura.id}/download`}>{factura.zip}</a>}</td>
                    <td>{factura.folio}</td>
                  </tr>
              )
              :
              <tr>
                <td className='text-center text-info' colSpan={4} ><strong>No hay registros disponibles</strong></td>
              </tr>
          }
          </tbody>
        </table>
      </div>
    </div>
  )
}

const DetalleReferencia = ({ pagos, solicitud, campos }) => {

  function getMontoTotal() {
    if (solicitud.tipoPago === TIPO_PAGO.UNICO) {
      return solicitud.monto;
    }
    return campos.length > 0 ? campos[0].monto : 0;
  }

  function getFacturas() {
    let facturas =pagos.map((pago)=> pago.factura );
    return facturas.filter((factura, idx) =>
      factura &&
        facturas.findIndex( item =>
          item &&  item.folio === factura.folio) === idx
    );
  }

  function getLastPago() {
    return pagos.length > 0 ?
      pagos.reduce(
        (acc, pago) =>
          pago.id > acc.id ? pago : acc, pagos[0]) : null;
  }

  return(
    <div className='row mt-20'>
      <DatosSolicitud
        solicitud={solicitud}
        montoTotal={getMontoTotal()}
        campos={campos}
        pago={getLastPago()}
      />
      <HistorialPagos
        pagos={pagos}
      />

      <HistorialFacturas
        facturas={getFacturas()}
      />

      <ExpedienteSolicitud
          solicitud={solicitud}
          campos={campos}
          pago={getLastPago()}
      />
    </div>
  )

}

export default DetalleReferencia;

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <DetalleReferencia
      pagos={window.PAGOS}
      solicitud={window.SOLICITUD}
      campos={window.CAMPOS}
    />,
    document.getElementById('detalle-referencia-component')
  )
});
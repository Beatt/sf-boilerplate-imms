import React from 'react';
import ReactDOM from 'react-dom'
import {
  getActionNameByInstitucionEducativa,
  isActionDisabledByInstitucionEducativa
} from "../../utils"
import { SOLICITUD } from "../../constants"
const DEFAULT_DOCUMENT_VALUE = '-'

const ListaCampos = ({ solicitud }) => {

  function handleStatusAction(solicitud) {
    if (isActionDisabledByInstitucionEducativa(solicitud.estatus)) return;

    let redirectRoute = ''
    switch (solicitud.estatus) {
      case SOLICITUD.CONFIRMADA:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/registrar-montos`
        break
      case SOLICITUD.MONTOS_INCORRECTOS_CAME:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/corregir-montos`
        break
      case SOLICITUD.CARGANDO_COMPROBANTES:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/campos-clinicos`
        break
      case SOLICITUD.MONTOS_VALIDADOS_CAME:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/seleccionar-forma-de-pago`
        break
      case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/detalle-de-forma-de-pago`
        break
    }

    window.location.href = redirectRoute
  }

  function isComprobantesPagoEmpty() {
    return solicitud.expediente.comprobantesPago.length === 0;
  }
  function isFacturasEmpty() {
    return solicitud.expediente.facturas.length === 0;
  }

  function getTotalCamposClinicos() {
    return solicitud.camposClinicos.length;
  }

  return (
    <div className='row'>
      <div className="col-md-12">
        <p><span className="text-bold">No. solicitud:</span> {solicitud.noSolicitud}</p>
        <div className="row">
          <div className="col-md-6 mt-10">
            <p><strong>Estado de la solicitud:</strong> {solicitud.estatus}</p>
          </div>
          <div className="col-md-6">
            <strong>Acción</strong>&nbsp;
            <button
              className='btn btn-default'
              disabled={isActionDisabledByInstitucionEducativa(solicitud.estatus)}
              onClick={() => handleStatusAction(solicitud)}
            >
              {getActionNameByInstitucionEducativa(solicitud.estatus, false)}
            </button>
          </div>
        </div>
      </div>
      <div className="col-md-12 mt-20">
        <p>Se autorizaron {getTotalCamposClinicos()} de {solicitud.totalCamposClinicosAutorizados} campos clínicos</p>
      </div>
      <div className="col-md-12 mt-10">
        <div className="panel panel-default">
          <div className="panel-body">
            <table className='table'>
              <thead className='headers'>
              <tr>
                <th>Sede</th>
                <th>Campo clínico</th>
                <th>Carrera</th>
                <th>No. lugares <br/>solicitados</th>
                <th>No. lugares <br/>autorizados</th>
                <th>Periodo</th>
                <th>No. de semanas</th>
              </tr>
              </thead>
              <tbody>
              {
                solicitud.camposClinicos.map((campoClinico, index) =>
                  <tr key={index}>
                    <td>{campoClinico.unidad.nombre ? campoClinico.unidad.nombre : 'No asignado'}</td>
                    <td>{campoClinico.convenio.cicloAcademico ? campoClinico.convenio.cicloAcademico.nombre : 'No asignado'}</td>
                    <td>{campoClinico.convenio.carrera.nivelAcademico.nombre}. {campoClinico.convenio.carrera.nombre}</td>
                    <td>{campoClinico.lugaresSolicitados}</td>
                    <td>{campoClinico.lugaresAutorizados}</td>
                    <td>{campoClinico.fechaInicial} - {campoClinico.fechaFinal}</td>
                    <td>{campoClinico.noSemanas}</td>
                  </tr>
                )
              }
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div className="col-md-12">
        <p className="text-bold mt-10 mb-10">Expediente</p>
        <div className="panel panel-default">
          <div className="panel-body">
            <table className='table'>
              <thead className='headers'>
              <tr>
                <th className='col-md-3'>Documento</th>
                <th className='col-md-7'>Descripción</th>
                <th className='col-md-1'>Fecha</th>
                <th className='col-md-1'>Archivo</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td>{solicitud.expediente.oficioMontos.nombre}</td>
                <td>{solicitud.expediente.oficioMontos.descripcion || DEFAULT_DOCUMENT_VALUE}</td>
                <td>{solicitud.expediente.oficioMontos.fecha || DEFAULT_DOCUMENT_VALUE}</td>
                <td>
                  {
                    solicitud.expediente.oficioMontos.urlArchivo ?
                      <a href={solicitud.expediente.oficioMontos.urlArchivo}>Descargar</a> :
                      DEFAULT_DOCUMENT_VALUE
                  }
                </td>
              </tr>
              {
                !isComprobantesPagoEmpty() &&
                <tr>
                  <td>{solicitud.expediente.comprobantesPago[0].nombre}</td>
                  <td>{solicitud.expediente.comprobantesPago[0].descripcion || DEFAULT_DOCUMENT_VALUE}</td>
                  <td>
                    {
                      solicitud.expediente.comprobantesPago.map((comprobantePago, index) =>
                        <p key={index}>{comprobantePago.fecha}</p>
                      )
                    }
                  </td>
                  <td>
                    {
                      solicitud.expediente.comprobantesPago.map((comprobantePago, index) =>
                        <p key={index}>
                          <a
                            href={`/ie/solicitudes/${comprobantePago.options.solicitudId}/pagos/${comprobantePago.options.pagoId}/descargar-comprobante-de-pago`}
                            target='_blank'
                          >
                            Descargar
                          </a>
                        </p>
                      )
                    }
                  </td>
                </tr>
              }
              {
                !isFacturasEmpty() &&
                <tr>
                  <td>{solicitud.expediente.facturas[0].nombre}</td>
                  <td>{solicitud.expediente.facturas[0].descripcion || DEFAULT_DOCUMENT_VALUE}</td>
                  <td>
                    {
                      solicitud.expediente.facturas.map((factura, index) =>
                        <p key={index}>{factura.fecha}</p>
                      )
                    }
                  </td>
                  <td>
                    {
                      solicitud.expediente.facturas.map((factura, index) =>
                        <p key={index}><a href={factura.urlArchivo}>Descargar</a></p>
                      )
                    }
                  </td>
                </tr>
              }
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  )
}

ReactDOM.render(
  <ListaCampos
    solicitud={window.SOLICITUD_PROP}
    total={window.TOTAL_PROP}
    autorizado={window.AUTORIZADO_PROP}
    campos={window.CAMPOS_PROP}
    pago={window.PAGO_PROP}
  />,
  document.getElementById('detalle-solicitud-component')
);

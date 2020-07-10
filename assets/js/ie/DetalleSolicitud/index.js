import React from 'react';
import ReactDOM from 'react-dom'
import {
  getActionNameByInstitucionEducativa,
  isActionDisabledByInstitucionEducativa
} from "../../utils"
import { SOLICITUD } from "../../constants"
const DEFAULT_DOCUMENT_VALUE = '-'

const ListaCampos = (
  {
    solicitud,
    autorizado,
  }) => {

  function handleStatusAction(solicitud) {
    if (isActionDisabledByInstitucionEducativa(solicitud.estatus)) return;

    let redirectRoute = ''
    switch (solicitud.estatus) {
      case SOLICITUD.CONFIRMADA:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/registrar-montos`
        break;
      case SOLICITUD.MONTOS_INCORRECTOS_CAME:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/corregir-montos`
        break;
      case SOLICITUD.CARGANDO_COMPROBANTES:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/campos-clinicos`
        break;
    }

    window.location.href = redirectRoute
  }

  function isComprobantesPagoEmpty() {
    return solicitud.expediente.comprobantesPago.length === 0;
  }
  function isFacturasEmpty() {
    return solicitud.expediente.facturas.length === 0;
  }

  return (
    <div className='row'>
      <div className="col-md-12">
        <div className="row">
          <div className="col-md-6 mt-10">
            <p><strong>Estado:</strong> {solicitud.estatus}</p>
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
        <p>Se autorizaron {autorizado} de {solicitud.totalCamposClinicosAutorizados} campos clínicos</p>
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
                    <td>{new Date(campoClinico.fechaInicial).toLocaleDateString()} - {new Date(campoClinico.fechaFinal).toLocaleDateString()}</td>
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
                <th>Documento</th>
                <th>Descripcion</th>
                <th>Fecha</th>
                <th>Archivo</th>
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
                        <p key={index}><a href={comprobantePago.urlArchivo}>Descargar</a></p>
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

import React from 'react';
import ReactDOM from 'react-dom'
import { solicitudesGet } from "../api/camposClinicos"
import {
  getActionNameByInstitucionEducativa,
  isActionDisabledByInstitucionEducativa
} from "../../utils"
import { SOLICITUD } from "../../constants"
const DEFAULT_SEARCH_VALUE = ''

const ListaCampos = (
  {
    solicitud,
    total,
    autorizado,
    pago,
    campos
  }) => {

  const { useState, useEffect } = React
  const [ camposClinicos, setCamposClinicos ] = useState([])
  const [ isLoading, toggleLoading ] = useState(false)

  useEffect(() => getCamposClinicos(), [])

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

  function isPago() {
    return pago[0] !== undefined &&
      pago[0] !== '';
  }

  function isFactura() {
    return pago[0] && pago[0].factura;
  }

  function getCamposClinicos() {
    toggleLoading(true)

    solicitudesGet(
      solicitud,
      DEFAULT_SEARCH_VALUE
    ).then((res) => setCamposClinicos(res.camposClinicos)
    ).finally(() => toggleLoading(false))
  }

  return (
    <div className='row'>
      <div className="col-md-12">
        <div className="row">
          <div className="col-md-6 mt-10">
            <p><strong>Estado:</strong> {campos[0].solicitud.estatus}</p>
          </div>
          <div className="col-md-6">
            <strong>Acción</strong>&nbsp;
            <button
              className='btn btn-default'
              disabled={isActionDisabledByInstitucionEducativa(campos[0].solicitud.estatus)}
              onClick={() => handleStatusAction(campos[0].solicitud)}
            >
              {getActionNameByInstitucionEducativa(campos[0].solicitud.estatus, false)}
            </button>
          </div>
        </div>
      </div>
      <div className="col-md-12 mt-20">
        <p>Se autorizaron {autorizado} de {total} campos clínicos</p>
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
                isLoading ?
                  <tr>
                    <th className='text-center' colSpan={9}>Cargando información...</th>
                  </tr> :
                  camposClinicos.map((campoClinico, index) =>
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
                <th>Fecha</th>
                <th>Descripcion</th>
                <th>Archivo</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td>{campos[0].solicitud.documento ? campos[0].solicitud.documento : 'Oficio de Montos de Colgiatura e inscripción'}</td>
                <td>{campos[0].solicitud.fechaComprobante ? campos[0].solicitud.fechaComprobante : ''}</td>
                <td>{campos[0].solicitud.descripcion ? campos[0].solicitud.descripcion : ''}</td>
                <td><a href='#'>{campos[0].solicitud.urlArchivo ? campos[0].solicitud.urlArchivo : ''}</a></td>
              </tr>
              {
                isPago() ?
                  <tr>
                    <td>Comprobante de pago</td>
                    <td>{pago[0].fechaPago}</td>
                    <td>Pago ref: {pago[0].referenciaBancaria}</td>
                    <td><a href='#'>{pago[0].comprobantePago}</a></td>
                  </tr> :
                  <tr>
                    <td>Comprobante de pago</td>
                    <td/>
                    <td>No se ha cargado información</td>
                    <td/>
                  </tr>
              }
              {
                isFactura() ?
                  <tr>
                    <td>Factura (CFDI)</td>
                    <td>{pago[0].factura.fechaFacturacion}</td>
                    <td/>
                    <td><a href='#'>{pago[0].factura.zip}</a></td>
                  </tr> :
                  <tr>
                    <td>Factura (CFDI)</td>
                    <td/>
                    <td>No se solicitó factura</td>
                    <td/>
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

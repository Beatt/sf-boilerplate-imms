import * as React from 'react'
import ReactDOM from 'react-dom'
import {getActionNameByCampoClinico} from "../../utils";

const DetalleSolicitudMultiple = ({ camposClinicos }) => {

  function getComprobanteAction(campoClinico) {
    if(campoClinico.comprobante !== null) return campoClinico.comprobante

    return(
      <div>
        <label htmlFor="">{getActionNameByCampoClinico(campoClinico.estatus.nombre)}</label>
        <input
          type="file"
        />
      </div>
    )
  }

  return(
    <div className="panel panel-default">
      <div className="panel-body">
        <table className='table'>
          <thead className='headers'>
          <tr>
            <th>Sede</th>
            <th>Campo clínico</th>
            <th>Nivel</th>
            <th>Carrera</th>
            <th>No. de lugares <br/>solicitados</th>
            <th>No. de lugares <br/>autorizados</th>
            <th>Fecha inicio</th>
            <th>Fecha término</th>
            <th>Estado</th>
            <th>Comprobante</th>
            <th>Factura</th>
          </tr>
          </thead>
          <tbody>
          {
            camposClinicos.map((campoClinico, index) =>
              <tr key={index}>
                <th>{campoClinico.unidad.tipoUnidad.nombre}</th>
                <th>{campoClinico.convenio.cicloAcademico.nombre}</th>
                <th>{campoClinico.convenio.carrera.nivelAcademico.nombre}</th>
                <th>{campoClinico.convenio.carrera.nombre}</th>
                <th>{campoClinico.lugaresSolicitados}</th>
                <th>{campoClinico.lugaresAutorizados}</th>
                <th>{new Date(campoClinico.fechaInicial).toLocaleDateString()}</th>
                <th>{new Date(campoClinico.fechaFinal).toLocaleDateString()}</th>
                <th>{campoClinico.estatus.nombre}</th>
                <th>
                  {getComprobanteAction(campoClinico)}
                </th>
                <th>{campoClinico.factura}</th>
              </tr>
            )
          }
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default DetalleSolicitudMultiple

document.addEventListener('DOMContentLoaded',  () => {
  ReactDOM.render(
    <DetalleSolicitudMultiple
      camposClinicos={window.CAMPOS_CLINICOS_PROPS}
    />,
    document.getElementById('detalle_solicitud_multiple-component')
  )
})

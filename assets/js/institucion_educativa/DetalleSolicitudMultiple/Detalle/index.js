import * as React from 'react'
import { getActionNameByCampoClinico } from "../../../utils";
import { uploadComprobantePago } from "../../api/camposClinicos";
import { CAMPO_CLINICO } from "../../../constants";

const DetalleSolicitudMultiple = ({ initCamposClinicos }) => {
  const { useState } = React
  const [isLoading, setIsLoading] = useState(false)
  const [feedbackMessage, setFeedbackMessage] = useState('')
  const [camposClinicos, setCamposClinicos] = useState(initCamposClinicos)


  function getComprobanteAction(campoClinico) {
    const estatus = campoClinico.estatus.nombre
    switch(estatus) {
      case CAMPO_CLINICO.PENDIENTE_DE_PAGO:
      case CAMPO_CLINICO.PAGO_NO_VALIDO:
        return(
          <div style={{ position: 'relative' }}>
            <label htmlFor="">{!isLoading ?
              getActionNameByCampoClinico(estatus) :
              'Cargando....'
            }</label>
            <input
              type="file"
              onChange={({ target }) => handleUploadComprobantePago(campoClinico, target)}
            />
            {feedbackMessage && <span className='error-message'>{feedbackMessage}</span>}
          </div>
        )
      case CAMPO_CLINICO.PAGO:
        return(
          <button
            className='btn btn-default'
            disabled={true}
          >
            {getActionNameByCampoClinico(estatus)}
          </button>
        )
      case CAMPO_CLINICO.PAGO_VALIDADO_FOFOE:
      case CAMPO_CLINICO.PENDIENTE_FACTURA_FOFOE:
      case CAMPO_CLINICO.CREDENCIALES_GENERADAS:
        return(
          <div>
            <a
              href={campoClinico.comprobante}
              target='_blank'
            >
              Comprobante de pago
            </a><br/>
            [{getActionNameByCampoClinico(estatus)}]
          </div>
        )
    }
  }

  function getFactura(factura) {
    if(factura === 'Pendiente' || factura === 'No solicitada') return factura;

    return(
      <a href={`${factura}`}>Descargar factura</a>
    )
  }

  function handleUploadComprobantePago(campoClinico, target) {
    setIsLoading(true)
    setTimeout(() => {
      uploadComprobantePago(campoClinico.id, target.files)
        .then(res => {
          if(res.status) {
            campoClinico.estatus.nombre = CAMPO_CLINICO.PAGO
            setCamposClinicos([...camposClinicos])
            setFeedbackMessage(res.message)
          } else {
            setFeedbackMessage(res.errors.file[0])
          }
        })
        .catch(() => setFeedbackMessage('Lo sentimos, ha ocurrido un problema. Vuelte a intentar más tarde'))
        .finally(() => setIsLoading(false))
    }, 1000)
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
                <td>{campoClinico.unidad.tipoUnidad.nombre}</td>
                <td>{campoClinico.convenio.cicloAcademico.nombre}</td>
                <td>{campoClinico.convenio.carrera.nivelAcademico.nombre}</td>
                <td>{campoClinico.convenio.carrera.nombre}</td>
                <td>{campoClinico.lugaresSolicitados}</td>
                <td>{campoClinico.lugaresAutorizados}</td>
                <td>{new Date(campoClinico.fechaInicial).toLocaleDateString()}</td>
                <td>{new Date(campoClinico.fechaFinal).toLocaleDateString()}</td>
                <td>{campoClinico.estatus.nombre}</td>
                <td>
                  {getComprobanteAction(campoClinico)}
                </td>
                <td>{getFactura(campoClinico.factura)}</td>
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

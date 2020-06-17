import * as React from 'react'
import { getActionNameByCampoClinico } from "../../../utils"
import { uploadComprobantePago } from "../../api/camposClinicos"
import { CAMPO_CLINICO } from "../../../constants"
import Modal from 'react-modal'
Modal.setAppElement('body')
import InputMask from "react-input-mask";

const DetalleSolicitudMultiple = ({ initCamposClinicos }) => {
  const { useState } = React
  const [isLoading, setIsLoading] = useState(false)
  const [feedbackMessage, setFeedbackMessage] = useState('')
  const [camposClinicos, setCamposClinicos] = useState(initCamposClinicos)
  const [modalIsOpen, setModalIsOpen] = React.useState(false);

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

  function closeModal() {
    setModalIsOpen(false)
  }

  return(
    <div className="panel panel-default">
      <div className="panel-body">
        <table className='table'>
          <thead className='headers'>
          <tr>
            <th>Sede</th>
            <th>Campo clínico</th>
            <th>Carrera</th>
            <th>No. de lugares <br/>solicitados</th>
            <th>No. de lugares <br/>autorizados</th>
            <th>Periodo</th>
            <th>Estado</th>
            <th>Comprobante</th>
            <th>Factura</th>
          </tr>
          </thead>
          <tbody>
          {
            camposClinicos.map((campoClinico, index) =>
              <tr key={index}>
                <td>{campoClinico.unidad.nombre}</td>
                <td>{campoClinico.convenio.cicloAcademico.nombre}</td>
                <td>{campoClinico.convenio.carrera.nivelAcademico.nombre}. {campoClinico.convenio.carrera.nombre}</td>
                <td>{campoClinico.lugaresSolicitados}</td>
                <td>{campoClinico.lugaresAutorizados}</td>
                <td>{new Date(campoClinico.fechaInicial).toLocaleDateString()} - {new Date(campoClinico.fechaFinal).toLocaleDateString()}</td>
                <td>{campoClinico.estatus.nombre}</td>
                <td>
                  <button className="btn btn-success" onClick={() => {
                    setModalIsOpen(true)
                  }}>Cargar comprobante</button>
                </td>
                <td>{getFactura(campoClinico.factura)}</td>
              </tr>
            )
          }
          </tbody>
        </table>
        <Modal
          isOpen={modalIsOpen}
          contentLabel="Modal"
          style={{ content: { marginRight: 'auto', marginLeft: 'auto' } }}
        >
          <button
            type="button"
            className="close"
            data-dismiss="modal"
            aria-label="Close"
            onClick={closeModal}
          >
            <span aria-hidden="true">&times;</span>
          </button>
          <div className='row'>
            <div className="col-md-12">
              <h2 className='mb-5'>Corrección de pagos</h2>
              <p className='mb-30'>No. de Solicitud <strong>NS_0006</strong></p>
            </div>
            <div className="col-md-12 mb-20">
              <table className='table table-condensed'>
                <thead>
                <tr>
                  <th>No de referencia</th>
                  <th>Comprobante registrado</th>
                  <th>Fecha</th>
                  <th>Monto validado</th>
                </tr>
                </thead>
              </table>
            </div>
            <div className="col-md-12 mb-20">
              <p>Monto total del campo clínico: <strong>$37,000.00</strong></p>
            </div>
            <div className="col-md-12">
              <h3 className='mb-5'>Observaciones</h3>
              <div className="alert alert-info">
                <p>La cantidad depositada no corresponde, faltan $2000.00 MN por cubrir, favor de cubrir el faltante</p>
              </div>
            </div>
            <div className="col-md-12">
              <h3 className='mb-5'>Registrar nuevo comprobante de pago</h3>
              <p className='mb-20'>Monto total del campo clínico por pagar: <strong>200,000</strong></p>
              <form action="" className='form-horizontal'>
                <div className="form-group">
                  <label htmlFor="" className='control-label col-md-4'>Fecha en que se realizó el nuevo pago:</label>
                  <div className="col-md-3">
                    <InputMask
                      mask="99/99/9999"
                      className='form-control'
                      onChange={(value) => {}}
                    />
                  </div>
                </div>
                <div className="form-group">
                  <label htmlFor="" className='control-label col-md-4'>
                    Monto del comprobante de nuevo pago:<br/>
                    <span className='text-danger text-sm'>NOTA: El monto debe coincidir con el comprobante registrado</span>
                  </label>
                  <div className="col-md-3">
                    <div className="input-group">
                      <input type="number" className='form-control'/>
                      <div className="input-group-addon">$</div>
                    </div>
                  </div>
                </div>
                <div className="form-group">
                  <label htmlFor="" className='control-label col-md-4'>Cargar comprobante del nuevo pago</label>
                  <div className="col-md-3">
                    <input type="file" id='comprobante-file' className='form-control'/>
                  </div>
                </div>
                <div className="row mt-30">
                  <div className="col-md-4"/>
                  <div className="col-md-2">
                    <button
                      type='button'
                      className='btn btn-default btn-block'
                      onClick={closeModal}
                    >
                      Cancelar
                    </button>
                  </div>
                  <div className="col-md-2">
                    <button
                      type='button'
                      className='btn btn-success btn-block'>
                      Guardar
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </Modal>
      </div>
    </div>
  )
}

export default DetalleSolicitudMultiple

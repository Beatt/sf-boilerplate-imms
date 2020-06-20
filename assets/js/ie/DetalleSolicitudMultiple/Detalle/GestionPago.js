import * as React from 'react'
import InputMask from "react-input-mask";
import Modal from "react-modal";

const GestionPago = (
  {
    modalIsOpen,
    closeModal,
    pagoId
  }
) => {
  const { useEffect, useState } = React

  const [gestionPago, setGestionPago] = useState({})

  useEffect(() => {
    
  }, [])

  return(
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
          <h2 className='mb-5'>Gestión de pagos</h2>
          <p className='mb-5'>No. de Solicitud <strong>NS_0006</strong></p>
          <p className='mb-20'>Monto total del campo clínico: <strong>$37,000.00</strong></p>
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
        <div className="col-md-12">
          <h3 className='mb-5'>Observaciones</h3>
          <div className="alert alert-info">
            <p>La cantidad depositada no corresponde, faltan $2000.00 MN por cubrir, favor de cubrir el faltante</p>
          </div>
        </div>
        <div className="col-md-12">
          <h3 className='mb-5'>Registrar comprobante de pago</h3>
          <p className='mb-20'>Monto total del campo clínico por pagar: <strong>200,000</strong></p>
          <form
            action={`/ie/cargar-comprobante-de-pago/${pagoId}`}
            method='post'
            className='form-horizontal'
            encType='multipart/form-data'
          >
            <div className="form-group">
              <label
                htmlFor="comprobante_pago_fechaPago"
                className='control-label col-md-4'
              >
                Fecha en que se realizó el nuevo pago:
              </label>
              <div className="col-md-3">
                <InputMask
                  mask="99/99/9999"
                  id='comprobante_pago_fechaPago'
                  className='form-control'
                  name='comprobante_pago[fechaPago]'
                />
              </div>
            </div>
            <div className="form-group">
              <label
                htmlFor="comprobante_pago_monto"
                className='control-label col-md-4'
              >
                Monto del comprobante de nuevo pago:<br/>
                <span className='text-danger text-sm'>NOTA: El monto debe coincidir con el comprobante registrado</span>
              </label>
              <div className="col-md-3">
                <div className="input-group">
                  <input
                    type="number"
                    id='comprobante_pago_monto'
                    name='comprobante_pago[monto]'
                    className='form-control'
                  />
                  <div className="input-group-addon">$</div>
                </div>
              </div>
            </div>
            <div className="form-group">
              <label
                htmlFor="comprobante_pago_comprobantePagoFile"
                className='control-label col-md-4'
              >
                Cargar comprobante del nuevo pago
              </label>
              <div className="col-md-3">
                <input
                  type="file"
                  id='comprobante_pago_comprobantePagoFile'
                  name='comprobante_pago[comprobantePagoFile]'
                  className='form-control'
                />
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
                  type='submit'
                  className='btn btn-success btn-block'>
                  Guardar
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </Modal>
  )
}

export default GestionPago

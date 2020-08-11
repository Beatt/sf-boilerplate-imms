import * as React from 'react';
import ReactDOM from 'react-dom';
import {getSchemeAndHttpHost, moneyFormat} from "../../utils";
import Cleave from "cleave.js/react";
import { TIPO_PAGO } from "../../constants";
const SI_REQUIERE_FACTURA_DEFAULT = 1;
const NO_REQUIERE_FACTURA_DEFAULT = 0;

const CargaDeComprobanteDePago = (
    {
      gestionPago,
      pagoId,
      errors
    }
  ) => {
  const { useState } = React

  const [monto, setMonto] = useState(undefined)
  const [hasMontoError, setMontoError] = useState(false)

  function handleMonto({ target }) {
    setMonto(target.rawValue)
  }

  function isPagoMultiple() {
    return gestionPago.tipoPago === TIPO_PAGO.MULTIPLE;
  }

  function handleCargarComprobanteDePago(event) {
    event.preventDefault();

    if(parseInt(monto) >= parseInt(gestionPago.montoTotalPorPagar)) {
      setMontoError(false);
      event.target.submit()
      return;
    }

    setMontoError(true)
  }

  return(
    <div className='row'>
      <div className="col-md-12">
        <h2 className='mb-20'>Carga de comprobante de pago</h2>
        <div className="row">
          <div className="col-md-6">
            <p className='mb-5'>No. de Solicitud <strong>{gestionPago.noSolicitud}</strong></p>
            <p className='mb-5'>Tipo de pago <strong>{gestionPago.tipoPago}</strong></p>
            <p className='mb-20'>Monto total: <strong>{moneyFormat(gestionPago.montoTotal)}</strong></p>
          </div>
          {
            isPagoMultiple() &&
            <div className="col-md-6">
              <p className='mb-5'><strong>Campo clínico</strong></p>
              <p className='mb-5'>Sede: <strong>{gestionPago.campoClinico.sede}</strong></p>
              <p className='mb-5'>Carrera <strong>{gestionPago.campoClinico.carrera}</strong></p>
            </div>
          }
        </div>
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
          <tbody>
          {
            gestionPago.pagos.length !== 0 ?
              gestionPago.pagos.map((pago, index) =>
                <tr key={index}>
                  <td>{pago.referenciaBancaria}</td>
                  <td><a href={`${getSchemeAndHttpHost()}/ie/pagos/${pago.id}/descargar-comprobante-de-pago`}>Descargar</a></td>
                  <td>{pago.fechaPago}</td>
                  <td>{moneyFormat(pago.monto)}</td>
                </tr>
              ) :
              <tr>
                <td
                  className='text-center text-info'
                  colSpan={4}
                >
                  Aún no se ha cargado ningún comprobante de pago
                </td>
              </tr>
          }
          </tbody>
        </table>
      </div>
      {
        gestionPago.ultimoPago.observaciones &&
        <div className="col-md-12">
          <h3 className='mb-5'>Observaciones</h3>
          <div className="alert alert-info">
            <p>{gestionPago.ultimoPago.observaciones}</p>
          </div>
        </div>
      }
      <div className="col-md-12">
        <h3 className='mb-5'>Registrar comprobante de pago</h3>
        <p className='mb-20'>Monto total a pagar: <strong>{moneyFormat(gestionPago.montoTotalPorPagar)}</strong></p>
        <form
          action={`${getSchemeAndHttpHost()}/ie/pagos/${pagoId}/carga-de-comprobante-de-pago`}
          method='post'
          className='form-horizontal'
          encType='multipart/form-data'
          onSubmit={handleCargarComprobanteDePago}
        >
          <div className="form-group">
            <label
              htmlFor="comprobante_pago_fechaPago"
              className='control-label col-md-4'
            >
              Fecha en que se realizó el nuevo pago:
            </label>
            <div className="col-md-3">
              <input
                type="date"
                id='comprobante_pago_fechaPago'
                className='form-control'
                name='comprobante_pago[fechaPago]'
                required={true}
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
              <div className={`input-group ${hasMontoError && 'has-error'}`}>
                <div className="input-group-addon">$</div>
                <Cleave
                  options={{numeral: true, numeralThousandsGroupStyle: 'thousand'}}
                  className='form-control'
                  required={true}
                  onChange={handleMonto}
                />
                <input
                  type="hidden"
                  id='comprobante_pago_monto'
                  name='comprobante_pago[monto]'
                  defaultValue={monto}
                />
              </div>
              { hasMontoError && <span className='text-danger'>El monto registrado es menor al monto total a pagar.</span> }
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
                required={true}
              />
            </div>
          </div>
          <div className="form-group">
            <label
              htmlFor='comprobante_pago_requiere_factura'
              className="control-label col-md-4 text-right"
            >
              ¿Requiere factura?&nbsp;
            </label>
            <div className="col-md-3">
              <label htmlFor='comprobante_pago_requiereFactura_yes'>Si&nbsp;</label>
              <input
                type="radio"
                value={SI_REQUIERE_FACTURA_DEFAULT}
                id='comprobante_pago_requiereFactura_yes'
                name='comprobante_pago[requiereFactura]'
                required={true}
              />
              &nbsp;&nbsp;&nbsp;&nbsp;
              <label htmlFor="comprobante_pago_requiereFactura_no">No&nbsp;</label>
              <input
                type="radio"
                value={NO_REQUIERE_FACTURA_DEFAULT}
                id='comprobante_pago_requiereFactura_no'
                name='comprobante_pago[requiereFactura]'
                required={true}
              />
            </div>
          </div>
          <div className="row mt-30">
            <div className="col-md-4"/>
            <div className="col-md-2">
              <a
                href={`${getSchemeAndHttpHost()}/ie/inicio`}
                className='btn btn-default btn-block'
              >Cancelar</a>
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
  )
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <CargaDeComprobanteDePago
      gestionPago={window.GESTION_PAGO_PROPS}
      pagoId={window.PAGO_ID_PROPS}
      errors={window.ERRORS_PROPS}
    />,
    document.getElementById('cargar-de-comprobante-de-pago-component')
  )
})

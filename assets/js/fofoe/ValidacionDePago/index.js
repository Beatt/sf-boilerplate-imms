import * as React from 'react';
import ReactDOM from 'react-dom';
import Cleave from "cleave.js/react";
import { TIPO_PAGO } from "../../constants";
import { moneyFormat } from "../../utils";
const SI_REQUIERE_FACTURA_DEFAULT = 1
const NO_REQUIERE_FACTURA_DEFAULT = 0

const ValidacionDePago = ({ pago }) => {

  function isPagoMultiple() {
    return pago.solicitud.tipoPago === TIPO_PAGO.MULTIPLE;
  }

  return(
    <div className='row mt-20'>
      <div className="col-md-12 mb-20">
        <div className="row">
          <div className="col-md-6">
            <p className='mb-5'>No. de Solicitud <strong>{pago.solicitud.noSolicitud}</strong></p>
            <p className='mb-5'>Tipo de pago <strong>{pago.solicitud.tipoPago}</strong></p>
            <p className='mb-20'>Monto total: <strong>{moneyFormat(pago.montoTotal)}</strong></p>
          </div>
          {
            isPagoMultiple() &&
            <div className="col-md-6">
              <p className='mb-5'><strong>Campo clínico</strong></p>
              <p className='mb-5'>Sede: <strong>{pago.solicitud.campoClinico.sede}</strong></p>
              <p className='mb-5'>Carrera <strong>{pago.solicitud.campoClinico.carrera}</strong></p>
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
            <tr>
              <td
                className='text-center text-info'
                colSpan={4}
              >
                Aún no se ha validado ningún comprobante de pago
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div className="col-md-12">
        <h3 className='mb-10'>Validar comprobante de pago</h3>
        <p>Monto pendiente a validar: <strong>{moneyFormat(pago.montoPendienteValidar)}</strong></p>
        <p className='mb-20'>Comprobante de pago a validar:&nbsp;&nbsp;
          <a
            href={`/fofoe/pagos/${pago.id}/descargar-comprobante-de-pago`}
            target='_blank'
          >
            Descargar
          </a>
        </p>
        <form
          action={`/ie/pagos/cargar-comprobante-de-pago`}
          method='post'
          className='form-horizontal'
          encType='multipart/form-data'
        >
          <div className="form-group">
            <label
              htmlFor="validacion_pago_fechaPago"
              className='control-label col-md-4'
            >
              Fecha en que se realizó el nuevo pago:
            </label>
            <div className="col-md-3">
              <input
                type="date"
                id='validacion_pago_fechaPago'
                className='form-control'
                name='validacion_pago[fechaPago]'
                required={true}
                defaultValue={pago.fechaPago}
              />
            </div>
          </div>
          <div className="form-group">
            <label
              htmlFor="validacion_pago_monto"
              className='control-label col-md-4'
            >
              Monto del comprobante de nuevo pago:<br/>
              <span className='text-danger text-sm'>NOTA: El monto debe coincidir con el comprobante registrado</span>
            </label>
            <div className="col-md-3">
              <div className={`input-group`}>
                <Cleave
                  options={{numeral: true, numeralThousandsGroupStyle: 'thousand'}}
                  className='form-control'
                  required={true}
                  value={pago.monto}
                />
                <div className="input-group-addon">$</div>
                <input
                  type="hidden"
                  id='validacion_pago_monto'
                  name='validacion_pago[monto]'
                />
              </div>
            </div>
          </div>
          <div className="form-group">
            <label
              htmlFor='validacion_pago_requiere_factura'
              className="control-label col-md-4 text-right"
            >
              ¿Requiere factura?&nbsp;
            </label>
            <div className="col-md-3">
              <label htmlFor='validacion_pago_requiereFactura_yes'>Si&nbsp;</label>
              <input
                type="radio"
                value={SI_REQUIERE_FACTURA_DEFAULT}
                id='validacion_pago_requiereFactura_yes'
                name='validacion_pago[requiereFactura]'
                required={true}
                defaultChecked={pago.requiereFactura === true}
              />
              &nbsp;&nbsp;&nbsp;&nbsp;
              <label htmlFor="validacion_pago_requiereFactura_no">No&nbsp;</label>
              <input
                type="radio"
                value={NO_REQUIERE_FACTURA_DEFAULT}
                id='validacion_pago_requiereFactura_no'
                name='validacion_pago[requiereFactura]'
                required={true}
                defaultChecked={pago.requiereFactura === true}
              />
            </div>
          </div>
          <div className="form-group">
            <label
              htmlFor="validacion_pago_observaciones"
              className='control-label col-md-4'
            >
              Observaciones
            </label>
            <div className="col-md-5">
              <textarea
                rows={7}
                className='form-control'
                id='validacion_pago_observaciones'
                name='validacion_pago[observaciones]'
              />
            </div>
          </div>
          <div className="row mt-30">
            <div className="col-md-4"/>
            <div className="col-md-2">
              <a
                href='/fofoe/inicio'
                className='btn btn-default btn-block'
              >
                Cancelar
              </a>
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

export default ValidacionDePago;

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ValidacionDePago
      pago={window.PAGO_PROPS}
    />,
    document.getElementById('validacion-de-pago-component')
  )
});

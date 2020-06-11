import React from 'react';
import ReactDOM from 'react-dom'
import camelcaseKeys from 'camelcase-keys'
import { SOLICITUD } from "../../constants";

const Registrar = (
  {
    institucion,
    solicitud
  }) => {

  return (
    <form
      action={`/instituciones/${institucion.id}/solicitudes/${solicitud.id}/editarPago`}
      method="post"
      encType='multipart/form-data'
    >
    <div>
        <div className="row mt-10">
            <div className="col-md-6">
                <p className="mt-10">No de referencia:&nbsp; {solicitud.referenciaBancaria }</p>
                <p className="mt-10">Monto total del campo clínico:&nbsp; {solicitud.monto }</p>
            </div>
            <div className="col-md-6">
                <p>Comprobantes registrados:</p>
                <table className='table table-bordered'>
                    <thead className='headers'>
                    <tr>
                        <th>Comprobante Registrado</th>
                        <th>Fecha</th>
                        <th>Monto Validado</th>
                    </tr>
                    </thead>
                    <tbody>
                    {
                    solicitud.pagos.map((item) => (
                        <tr>
                            <td>{ item.comprobantePago }</td>
                            <td>{ item.fechaPago } </td>
                            <td>{ item.monto }</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
      </div>

      <div className="row mt-10">
        <div className="col-md-12">
            <p><strong>Observaciones:</strong></p>
            <p className="mt-10 mb-10">{ solicitud.pagos.observaciones }</p>
        </div>

        <div className="row">
            <div className="col-md-8">
                <p className="mt-10 mb-10"><strong>Registrar nuevo comprobante de pago</strong></p>
                <p className="mt-10 mb-10">Monto total del campo clínico a pagar: { parseInt(solicitud.monto) - parseInt(solicitud.pagos.reduce(function(a,b){ return parseInt(a) + parseInt(b.monto); }, 0)) }</p>
                <div className='hidden'>
                    <div className="form-group">
                    <input
                    className='form-control'
                    defaultValue={solicitud.id}
                    required={true}
                    name={`solicitud_comprobante_pago[pagos][${solicitud.pagos.length}][solicitud]`}
                    />
                    </div>
                </div>
                <div className='hidden'>
                    <div className="form-group">
                    <input
                    className='form-control'
                    defaultValue={solicitud.referenciaBancaria}
                    required={true}
                    name={`solicitud_comprobante_pago[pagos][${solicitud.pagos.length}][referenciaBancaria]`}
                    />
                    </div>
                </div>
                <div className="form-horizontal">
                    <div className="form-group">
                        <label className="forma-label">Fecha en la que se realizó el nuevo pago</label>
                        <input
                        className='form-control'
                        type="date"
                        required={true}
                        name={`solicitud_comprobante_pago[pagos][${solicitud.pagos.length}][fechaPago]`}
                        />
                        <span className="error-message">NOTA: El monto debe coincidir con el comprobante de pago.</span>
                    </div>
                </div>
                <div className="form-horizontal">
                    <div className="form-group">
                        <label className="forma-label">Monto del comprobante nuevo de pago</label>
                        <input
                        className='form-control'
                        type="number"
                        required={true}
                        name={`solicitud_comprobante_pago[pagos][${solicitud.pagos.length}][monto]`}
                        />
                        <span className="error-message">NOTA: El monto debe coincidir con el comprobante de pago.</span>
                    </div>
                </div>
                <div>
                    <input
                    type="file"
                    name={`solicitud_comprobante_pago[pagos][${solicitud.pagos.length}][comprobantePagoFile]`}
                    required={true}
                    />
                </div>
                <div className="row">
                    <div className="col-md-10"/>
                    <div className="col-md-2">
                        <button
                        type="submit"
                        className='btn btn-success btn-block'
                        >
                        Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
  </form>
  )
}


ReactDOM.render(
  <Registrar
    institucion={window.RFC_PROP}
    solicitud={window.SOLICITUD_PROP}
  />,
  document.getElementById('registrar-component')
);

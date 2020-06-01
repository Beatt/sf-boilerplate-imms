import React from 'react';
import ReactDOM from 'react-dom'
import camelcaseKeys from 'camelcase-keys'
import { SOLICITUD } from "../../constants";

const Registrar = (
  {
    rfc,
    solicitud
  }) => {

    {console.log(solicitud)}
  return (
    <form
      action={`/instituciones/${1}/solicitudes/${solicitud.id}/registrarPago`}
      method="post"
      encType='multipart/form-data'
    >
    <div>
        <div className="row mt-10">
            <div className="col-md-6">
                <p>RFC:&nbsp; {rfc}</p>
                <p className="mt-10">No de referencia:&nbsp; {solicitud.referenciaBancaria }</p>
            </div>
            <div className="col-md-6">
                <p><strong>Monto total</strong> por pagar: {solicitud.monto ? solicitud.monto : ""}</p>
                <p className="mt-10">No de comprobantes de pago aa registrar</p>
            </div>
            
            <div className="col-md-12 mb-10">
            <div className="panel panel-default">
                <div className="panel-body">
                <table className='table'>
                    <thead className='headers'>
                    <tr>
                    <th>Monto</th>
                    <th>Fecha de Pago</th>
                    <th>Comprobante</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td className='hidden'>
                              <div className="form-group">
                              <input
                              className='form-control'
                              defaultValue={solicitud.id}
                              required={true}
                              name={`solicitud_comprobante_pago[pagos][0][solicitud]`}
                              />
                              </div>
                          </td>
                          <td className='hidden'>
                              <div className="form-group">
                              <input
                              className='form-control'
                              defaultValue={solicitud.referenciaBancaria}
                              required={true}
                              name={`solicitud_comprobante_pago[pagos][0][referenciaBancaria]`}
                              />
                              </div>
                          </td>
                          <td>
                              <div className="form-group">
                              <input
                              className='form-control'
                              type="number"
                              defaultValue={'0'}
                              required={true}
                              name={`solicitud_comprobante_pago[pagos][0][monto]`}
                              />
                              </div>
                          </td>
                          <td>
                              
                          </td>
                          <td>
                          <input
                            type="file"
                            name='solicitud_comprobante_pago[pagos][0][comprobantePagoFile]'
                          />
                          </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>

            <div className="col-md-12 mb-10 mt-10 observaciones">
                <p className="observaciones"><strong>Nota:</strong> El monto a registrar debe coincidor con el comprobante registrado</p>
                <p className="observaciones">La suma total de los montos registrados debe ser igual al monto total.</p>
                
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
  </form>
  )
}


ReactDOM.render(
  <Registrar
    rfc={window.RFC_PROP}
    solicitud={window.SOLICITUD_PROP}
  />,
  document.getElementById('registrar-component')
);

import * as React from 'react';
import ReactDOM from 'react-dom';

const ValidacionDePago = () => {

  function isPagoMultiple() {
    return true;
  }

  return(
    <div className='row mt-20'>
      <div className="col-md-12">
        <div className="row">
          <div className="col-md-6">
            <p className='mb-5'>No. de Solicitud <strong>Numero de solicitud</strong></p>
            <p className='mb-5'>Tipo de pago <strong>Tipo de pago</strong></p>
            <p className='mb-20'>Monto total: <strong>Monto total</strong></p>
          </div>
          {
            isPagoMultiple() &&
            <div className="col-md-6">
              <p className='mb-5'><strong>Campo clínico</strong></p>
              <p className='mb-5'>Sede: <strong>Sede</strong></p>
              <p className='mb-5'>Carrera <strong>Carrera</strong></p>
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
                Aún no se ha cargado ningún comprobante de pago
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default ValidacionDePago;

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ValidacionDePago/>,
    document.getElementById('validacion-de-pago-component')
  )
});

import React from 'react';
import ReactDOM from 'react-dom'

const Registrar = (
  {
    institucion,
    solicitud
  }) => {

  const [total, setTotal] = React.useState(0);

  const handleChecked = (event) => {
    let subtotal = total;
    if (event.checked) subtotal += parseInt(solicitud.pagos[event.id].monto);
    else subtotal -= parseInt(solicitud.pagos[event.id].monto);
    setTotal(subtotal);
  }

  return (
    <form
      action={`/fofoe/solicitudes/${solicitud.id}/registrar-factura`}
      method="post"
      encType='multipart/form-data'
    >
      <div>
        <div className="row mt-10">
          <div className="col-md-8">
            <p className="mt-10 mb-10">Pago por solicitud de Campo(s) Clínico(s)</p>
            <p className="mt-10 mb-10">Institución educativa: {institucion.nombre}</p>
            <p className="mt-10 mb-10">RFC: {institucion.rfc}</p>
            <p className="mt-10 mb-10">Delegación: </p>
            <p className="mt-10 mb-10">Referencia de pago: {solicitud.referenciaBancaria}</p>
            <p className="mt-10 mb-10">Monto total a pagar: ${solicitud.monto}</p>

          </div>
          <div className="col-md-4">
            <p className="mt-10 mb-10">No. de solicitud: {solicitud.noSolicitud}</p>
            <p className="mt-10 mb-10">Fecha de solicitud: {solicitud.fecha}</p>
          </div>
        </div>
        <div className="row mt-10">
          <div className="col-md-12">
            <p className="mt-10 mb-10"><strong>Comprobantes pendientes de facturación</strong></p>
            <p className="mt-10 mb-10">Seleccione los comprobantes de pago que estarán asociados a la factura a
              emitir.</p>
            <div className="form-inline mt-10 mb-10">
              <div className="form-group">
                <label>Monto total a facturar &nbsp;</label>
                <div className="input-group">
                  <div className="input-group-addon">$</div>
                  <input
                    className='form-control'
                    type="text"
                    readOnly={true}
                    value={total}
                    name={`factura[monto]`}
                  />
                </div>
              </div>
            </div>

            <div className="col-md-6">
              <table className='table'>
                <thead className='headers'>
                <tr>
                  <th>Comprobante de pago</th>
                  <th>Fecha</th>
                  <th>monto validado</th>
                </tr>
                </thead>
                <tbody>
                {
                  solicitud.pagos.map((item, index) =>
                    (item.requiereFactura == true) ?

                      <tr key={index}>

                        <td>
                          <input
                            type="checkbox"
                            onChange={e => handleChecked(e.target)}
                            id={index}
                          />
                        </td>
                        <td>{item.comprobantePago}</td>
                        <td>{item.fechaPago}</td>
                        <td>{item.monto}</td>
                      </tr>
                      : ''
                  )
                }
                </tbody>
              </table>
            </div>
            <div className="col-md-1"/>
            <div className="col-md-5">
              <p className="mb-10">Subir factura</p>
              <input
                type="file"
                name={`solicitud_comprobante_pago[pagos][0][factura][zipFile]`}
                required={true}
              />

              <button
                type="submit"
                className='btn btn-success btn-block'
              >
                Guardar
              </button>
            </div>
          </div>
        </div>

        <div className="row">
          <div className="col-md-12">
            <p className="mt-10 mb-10"><strong>Facturas generadas</strong></p>
          </div>
        </div>
      </div>
    </form>
  )
}


ReactDOM.render(
  <Registrar
    institucion={window.INSTITUCION_PROP}
    solicitud={window.SOLICITUD_PROP}
  />,
  document.getElementById('registrar-factura-component')
);

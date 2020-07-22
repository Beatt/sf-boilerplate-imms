import React from 'react';
import ReactDOM from 'react-dom';
import {dateFormat} from "../../utils";

const Registrar = (
  {
    institucion,
    solicitud
  }) => {

  const [total, setTotal] = React.useState(0);

  const handleChecked = (event) => {
    let subtotal = total;
    if (event.checked) subtotal += parseFloat(solicitud.pagos[event.id].monto);
    else subtotal -= parseFloat(solicitud.pagos[event.id].monto);
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
                    name={`solicitud_factura[pagos][0][factura][monto]`}
                    
                  />
                </div>
              </div>
            </div>
            <div className='hidden'>
              <div className="form-group">
                <input
                  className='form-control'
                  defaultValue={solicitud.id}
                  required={true}
                  name={`solicitud_factura[pagos][0][solicitud]`}
                />
              </div>
            </div>
            <div className='hidden'>
              <div className="form-group">
                <input
                  className='form-control'
                  defaultValue={solicitud.id}
                  required={true}
                  name={`solicitud_factura[pagos][0][factura][aux]`}
                />
              </div>
            </div>
            <div className="col-md-6">
              <table className='table'>
                <thead className='headers'>
                <tr>
                  <th></th>
                  <th>Comprobante de pago</th>
                  <th>Fecha</th>
                  <th>Monto validado</th>
                </tr>
                </thead>
                <tbody>
                {
                  solicitud.pagos.map((item, index) =>
                    (item.facturaGenerada == false && item.validado == true) ?
                      <tr key={index}>
                        <td>
                          <input
                            type="checkbox"
                            onChange={e => handleChecked(e.target)}
                            id={index}
                            disabled={!item.requiereFactura}
                            name={`solicitud_factura[pagos][${index}][facturaGenerada]`}
                          />
                        </td>
                        <td>{item.comprobantePago}</td>
                        <td>{dateFormat(item.fechaPago)}</td>
                        <td>{item.monto}</td>
                        <td>{item.referenciaBancaria}</td>
                      </tr>
                      :
                      <div></div>
                  )
                }
                </tbody>
              </table>
            </div>
            <div className="col-md-1"/>
            <div className="col-md-2">
              <p className="mb-10">Subir factura</p>
              <input
                type="file"
                name={`solicitud_factura[pagos][0][factura][zipFile]`}
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
          <div className="col-md-6">
            <div className="form-group col-md-12">
              <label>Folio de facturar &nbsp;</label>
                <input
                  className='form-control'
                  type="text"
                  name={`solicitud_factura[pagos][0][factura][folio]`}
                />
            </div>
          </div>

          <div className="col-md-6">
            <div className="form-group col-md-12">
                <label className="forma-label">Fecha de factura</label>
                <input
                className='form-control col-md-12'
                type="date"
                required={true}
                name={`solicitud_factura[pagos][0][factura][fechaFacturacion]`}
                />
                
            </div>
          </div>
        </div>

        <div className="row">
          <div className="col-md-12">
            <p className="mt-10 mb-10"><strong>Facturas generadas</strong></p>
            <table className='table'>
                <thead className='headers'>
                <tr>
                  <th>Fecha Facturación</th>
                  <th>Monto Facturado</th>
                  <th>Comprobante</th>
                  <th>Archivo Factura</th>
                  <th>Folio Factura</th>
                </tr>
                </thead>
                <tbody>
                {
                  solicitud.pagos.map((item, index) =>
                  (item.factura) ?
                      <tr key={index}>
                        <td>{dateFormat(item.factura.fechaFacturacion)}</td>
                        <td>{item.factura.monto}</td>
                        <td>{item.comprobantePago && <a href={item.comprobantePago} download>{item.comprobantePago}</a>}</td>
                        <td>{item.factura.zip && <a href={item.factura.zip} download>{item.factura.zip}</a>}</td>
                        <td>{item.factura.folio}</td>
                      </tr>
                      :
                      ''
                  )
                }
                </tbody>
              </table>
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

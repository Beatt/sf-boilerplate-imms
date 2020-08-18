import React from 'react';
import ReactDOM from 'react-dom';
import {dateFormat} from "../../utils";
import Cleave from 'cleave.js/react';


const Registrar = (
  {
    institucion,
    solicitud,
    pagos,
    action
  }) => {


  let monto = 0;

  let factura = false;

  pagos.forEach(element => {
    if(element.facturaGenerada != true)
      monto += parseFloat(element.monto);

    if(element.factura)
      factura = true; 
  });

  console.log(factura);

  const [total, setTotal] = React.useState(monto);

  const handleChecked = (event) => {
    let subtotal = total;
    if (event.checked) subtotal += parseFloat(pagos[event.id].monto);
    else subtotal -= parseFloat(pagos[event.id].monto);
    setTotal(subtotal);
  }

  return (
    <form
      action={action}
      method="post"
      encType='multipart/form-data'
    >
      <div>
        <div className="row mt-10">
          <div className="col-md-8">
            <p className="mt-10 mb-10">Pago por solicitud de Campo(s) Clínico(s)</p>
            <p className="mt-10 mb-10">Institución educativa: {institucion.nombre}</p>
            <p className="mt-10 mb-10">RFC: {institucion.rfc}</p>
            <p className="mt-10 mb-10">Cédula de identificación fiscal: {
                institucion.cedulaIdentificacion &&
                <a
                  href={`/fofoe/instituciones/${institucion.id}/descargar-cedula-de-identificacion`}
                  download
                >
                  Descargar cédula
                </a>
            }</p>
            <p className="mt-10 mb-10">Delegación: {solicitud.camposClinicos[0].convenio.delegacion.nombre}</p>
            <p className="mt-10 mb-10">Referencia de pago: {pagos[0].referenciaBancaria}</p>
            <div className="form-inline mt-10 mb-10">
              <div className="form-group">
                <label>Monto total a pagar &nbsp;</label>
                  <div className={`input-group`}>
                    <div className="input-group-addon">$</div>
                    <Cleave
                      options={{numeral: true, numeralThousandsGroupStyle: 'thousand'}}
                      className='mt-10 mb-10 form-control col-md-1'
                      value={"$ " + solicitud.monto}
                      disabled={true}
                    />
                  </div>
            </div>
          </div>
            

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
                  <Cleave
                      options={{numeral: true, numeralThousandsGroupStyle: 'thousand'}}
                      className='mt-10 mb-10 form-control col-md-1'
                      value={total}
                      disabled={true}
                    />
                  <input
                    className='form-control'
                    type="hidden"
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
                  <th>Referencia Bancaria</th>
                </tr>
                </thead>
                <tbody>
                {
                  pagos.map((item, index) =>
                    //(item.facturaGenerada != true && item.validado == true) ?
                    (item.facturaGenerada != true) ?
                      <tr key={index}>
                        <td>
                          <input
                            type="checkbox"
                            //onChange={e => handleChecked(e.target)}
                            id={index}
                            checked={true}
                            name={`solicitud_factura[pagos][${index}][facturaGenerada]`}
                          />
                        </td>
                        <td><a href={`/fofoe/pagos/${item.id}/descargar-comprobante-de-pago`} download>{item.comprobantePago}</a></td>
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
            </div>
          </div>
        </div>

        <div className="row">
          <div className="col-md-6">
            <div className="form-group col-md-12">
              <label>Folio de la factura &nbsp;</label>
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
                  factura ? 

                    pagos.map((item, index) =>
                    (item.factura) ?
                        <tr key={index}>
                          <td>{dateFormat(item.factura.fechaFacturacion)}</td>
                          <td>{item.factura.monto}</td>
                          <td>{item.comprobantePago && <a href={`/fofoe/pagos/${item.id}/descargar-comprobante-de-pago`} download>{item.comprobantePago}</a>}</td>
                          <td>{item.factura.zip && <a href={item.factura.zip} download>{item.factura.zip}</a>}</td>
                          <td>{item.factura.folio}</td>
                        </tr>
                        :
                        ''
                    )
                    :
                    <tr>
                      <td className='text-center text-info' colSpan={5} ><strong>No hay registros disponibles</strong></td>
                    </tr>
                }
                </tbody>
              </table>
          </div>
        </div>
        <div className='row'>
          <div className='col-md-10'></div>
          <div className='col-md-2'>
              <button
                type="submit"
                className='btn btn-success btn-block'
              >
                Guardar
              </button>
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
    pagos={window.PAGOS_PROP}
    action={window.ACTION}
  />,
  document.getElementById('registrar-factura-component')
);

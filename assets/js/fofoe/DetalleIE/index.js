import * as React from 'react'
import ReactDOM from 'react-dom'
import ListConvenios from "../../components/ListConvenios";
import {dateFormat, getSchemeAndHttpHost} from "../../utils";

const ValidarInfo = (
  {
    institucion,
    errores,
    pagos
  }) => {

  return(
    <div
      
    >
    <label className="mb-10">Información Institución</label>

      <div className='row'>
        <div className='col-md-6'>
          <div className={`form-group ${errores.rfc ? 'has-error has-feedback' : ''}`}>
            <label htmlFor="institucion_rfc">RFC</label>
            <input
              className='form-control'
              type="text"
              name="institucion[rfc]"
              id="institucion_rfc"
              defaultValue={institucion.rfc}
              disabled={true}
            />
            <span className="help-block">{errores.rfc ? errores.rfc[0] : ''}</span>
          </div>
        </div>
        <div className='col-md-6'>
          <div className={`form-group ${errores.representante ? 'has-error has-feedback' : ''}`}>
            <label htmlFor="institucion_representante">Representante</label>
            <input
              className='form-control'
              type="text"
              name="institucion[representante]"
              id="institucion_representante"
              defaultValue={institucion.representante}
              disabled={true}
            />
            <span className="help-block">{errores.representante ? errores.representante[0] : ''}</span>
          </div>
        </div>
      </div>
      <div className='row'>
        <div className="col-md-12">
          <div className={`form-group ${errores.direccion ? 'has-error has-feedback' : ''}`}>
            <label htmlFor="institucion_direccion">Domicilio</label>
            <input
              className='form-control'
              type="text"
              name="institucion[direccion]"
              id="institucion_direccion"
              defaultValue={institucion.direccion}
              disabled={true}
            />
            <span className="help-block">{errores.direccion ? errores.direccion[0] : ''}</span>
          </div>
        </div>
      </div>

      <div className='row'>
        <div className="col-md-4">
          <div className='form-group'>
            <label htmlFor="institucion_correo">Correo</label>
            <input
              className='form-control'
              type="email"
              name="institucion[correo]"
              id="institucion_correo"
              defaultValue={institucion.correo}
              disabled={true}
            />
          </div>
        </div>

        <div className="col-md-3">
          <div className={`form-group ${errores.telefono ? 'has-error has-feedback' : ''}`}>
            <label htmlFor="institucion_telefono">Teléfono</label>
            <input
              className='form-control'
              type="text"
              name="institucion[telefono]"
              id="institucion_telefono"
              defaultValue={institucion.telefono}
              disabled={true}
            />
            <span className="help-block">{errores.telefono ? errores.telefono[0] : ''}</span>
          </div>
        </div>

        <div className="col-md-2">
          <div className={`form-group ${errores.extension ? 'has-error has-feedback' : ''}`}>
            <label htmlFor="institucion_extension">Extensión</label>
            <input
              className='form-control'
              type="text"
              name="institucion[extension]"
              id="institucion_extension"
              defaultValue={institucion.extension}
              disabled={true}
            />
            <span className="help-block">{errores.extension ? errores.extension[0] : ''}</span>
          </div>
        </div>

        <div className="col-md-3">
          <div className='form-group'>
            <label htmlFor="institucion_fax">Fax (opcional)</label>
            <input
              className='form-control'
              type="text"
              name="institucion[fax]"
              id="institucion_fax"
              defaultValue={institucion.fax}
              disabled={true}
            />
          </div>
        </div>
      </div>

      <div className='row'>
        <div className="col-md-4">
          <div className='form-group' >
            <label htmlFor="institucion_sitioWeb">Página web (opcional)</label>
            <input className='form-control'
              type="text"
              name="institucion[sitioWeb]"
              id="institucion_sitioWeb"
              defaultValue={institucion.sitioWeb}
              disabled={true}
            />
          </div>
        </div>
      </div>

      <div className="row">
        <div className="col-md-8">
            <label>Constancia de Situación Fiscal:&nbsp; </label>
            {
                institucion.cedulaIdentificacion &&
                <a
                  href={`${getSchemeAndHttpHost()}/fofoe/${institucion.id}/descargar-cedula-de-identificacion`}
                  target='_blank' download
                  download
                >
                  Descargar cédula
                </a>
            }
        </div>
      </div>

        <div className="row mt-10">
            <div className="col-md-12">
                <table className='table table-bordered'>
                    <thead className='headers'>
                    <tr>
                        <th>No. Solicitud</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Pago</th>
                        <th>Monto</th>
                        <th>Factura</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    {
                      pagos.map((item) => (
                        <tr>
                          <td>{item.noSolicitud}</td>
                          <td>{item.fecha ? item.fecha : 'No asignada'}</td>
                          <td><ul>{item.pagos.map(pagos => {
                            return (<li>{pagos.fechaPago ? pagos.fechaPago : 'No asignada'}</li>);
                          })}</ul></td>

                          <td><ul>{item.pagos.map(pagos => {
                            return (<li>{pagos.monto ? parseFloat(pagos.monto).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : 'No asignada'}</li>);
                          })}</ul></td>

                          <td><ul>{item.pagos.map(pagos => {
                            return (<li>{pagos.requiereFactura ? pagos.factura ? pagos.factura.zip : 'Pendiente de factura' : 'No solicito'}</li>);
                          })}</ul></td>

                          <td><ul>{item.pagos.map(pagos => {
                            return (<li>{pagos.validado ? (pagos.validado == true ? 'Pagada' : 'Pendiente de validación') : 'Pendiente de validación'}</li>);
                          })}</ul></td>

                        </tr>
                      ))
                    }
                    </tbody>
                </table>
            </div>
        </div>

    </div>
  )
}

export default ValidarInfo

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ValidarInfo
      institucion={window.INSTITUCION_PROP}
      errores={window.ERRORS}
      action={window.ACTION}
      pagos={window.PAGOS_PROP}
    />,
    document.getElementById('perfil-component')
  )

  ReactDOM.render(
    <ListConvenios
      convenios={window.CONVENIOS_PROP}
    />,
    document.getElementById('lista-convenio-component')
  )
})

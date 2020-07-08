import * as React from 'react'
import ReactDOM from 'react-dom'
import ListConvenios from "../../components/ListConvenios";

const ValidarInfo = (
  {
    institucion,
    errores,
    action
  }) => {

  return(
    <form
      action={action}
      method="post"
      encType='multipart/form-data'
    >
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
              required={true}
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
              required={true}
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
              required={true}
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
              required={true}
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
              required={true}
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
            />
          </div>
        </div>
        <div className="col-md-8">
          <label htmlFor="institucion_cedulaFile">
            Cargue Cédula de Identificación Fiscal de la institución educativa (Opcional)<br/>
            <span className='text-danger text-sm'>Por favor verifique que los datos que aparecen en su archivo sean correctos. <br/>
            La cédula de identificación fiscal se utilizará para emitir las facturas de sus pagos.</span>
          </label>
          <input
            type="file"
            name="institucion[cedulaFile]"
            id="institucion_cedulaFile"
            required={false}
          />
          <span className="help-block">{errores.cedulaFile ? errores.cedulaFile[0] : ''}</span>
          {
            institucion.cedulaIdentificacion &&
            <a
              href={institucion.cedulaIdentificacion}
              download
            >
              Descargar cédula
            </a>
          }
        </div>
        <div className="col-md-12 mt-15 text-right">
          <div className='form-group'>
            <label htmlFor='institucion_isConfirmacionInformacion'>
              Declaro que la información aquí descrita esta correcta y actualizada
            </label>&nbsp;
            <input
              type="checkbox"
              name='institucion[isConfirmacionInformacion]'
              id='institucion_isConfirmacionInformacion'
              value='checked'
              defaultChecked={institucion.confirmacionInformacion !== null}
              required={true}
            />
          </div>
        </div>
      </div>

      <div className='row'>
        <div className="col-md-9"/>
        <div className='col-md-3'>
          <button type="submit" className="btn btn-success btn-block">Guardar</button>
        </div>
      </div>
    </form>
  )
}

export default ValidarInfo

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ValidarInfo
      institucion={window.INSTITUCION_PROP}
      errores={window.ERRORS}
      action={window.ACTION}
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

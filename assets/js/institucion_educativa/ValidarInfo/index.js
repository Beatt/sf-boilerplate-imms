import * as React from 'react'
import ReactDOM from 'react-dom'

const ValidarInfo = ({ institucion }) => {
  return(
    <div className="row">
      <div className="col-md-12">
        <h2>Nombre: {institucion.nombre}</h2>
      </div>
      <div className="row">

        <form
          action={`/instituciones/${institucion.id}/editar`}
          method="post"
          encType='multipart/form-data'
        >
          <div className="col-md-6">
            <label htmlFor="institucion_rfc">RFC</label>
            <input
              type="text"
              name="institucion[rfc]"
              id="institucion_rfc"
              defaultValue={institucion.rfc}
            />
          </div>
          <div className="col-md-12">
            <label htmlFor="institucion_direccion">Domicilio</label>
            <input
              type="text"
              name="institucion[direccion]"
              id="institucion_direccion"
              defaultValue={institucion.direccion}
            />
          </div>
          <div className="col-md-6">
            <label htmlFor="institucion_correo">Correo</label>
            <input
              type="text"
              name="institucion[correo]"
              id="institucion_correo"
              defaultValue={institucion.correo}
            />
          </div>
          <div className="col-md-12">
            <label htmlFor="institucion_telefono">Telefono</label>
            <input
              type="text"
              name="institucion[telefono]"
              id="institucion_telefono"
              defaultValue={institucion.telefono}
            />
          </div>
          <div className="col-md-12">
            <label htmlFor="institucion_fax">Fax</label>
            <input
              type="text"
              name="institucion[fax]"
              id="institucion_fax"
              defaultValue={institucion.fax}
            />
          </div>
          <div className="col-md-12">
            <label htmlFor="institucion_sitioWeb">Página web</label>
            <input
              type="text"
              name="institucion[sitioWeb]"
              id="institucion_sitioWeb"
              defaultValue={institucion.sitioWeb}
            />
          </div>
          <div className="col-md-12">
            <label htmlFor="institucion_cedulaFile_file">Archivo</label>
            <input
              type="file"
              name="institucion[cedulaFile][file]"
              id="institucion_cedulaFile_file"
            />
          </div>
          <div className="hidden">
            <input
              type="checkbox"
              name="institucion[cedulaFile][delete]"
              id="institucion_cedulaFile_delete"
              checked={true}
            />
          </div>

          <button type="submit">Guardar</button>

        </form>

      </div>
    </div>
  )
}

export default ValidarInfo

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ValidarInfo
      institucion={window.INSTITUCION_PROP}
    />,
    document.getElementById('validar-info')
  )
})

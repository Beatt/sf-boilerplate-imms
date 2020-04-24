import * as React from 'react'
import ReactDOM from 'react-dom'

const ValidarInfo = ({ institucion }) => {
  return(
    <form
      action={`/instituciones/${institucion.id}/editar`}
      method="post"
      encType='multipart/form-data'
    >
      <div className='row'>
        <div className='col-md-3'>
          <div className='form-group'>
            <label htmlFor="institucion_rfc">RFC</label>
            <input className='form-control'
              type="text"
              name="institucion[rfc]"
              id="institucion_rfc"
              defaultValue={institucion.rfc}
            />
          </div>
        </div>
      </div>
      <div className='row'>
        <div className="col-md-9">
          <div className='form-group'>
            <label htmlFor="institucion_direccion">Domicilio</label>
            <input className='form-control'
              type="text"
              name="institucion[direccion]"
              id="institucion_direccion"
              defaultValue={institucion.direccion}
            />
          </div>
        </div>
      </div>
      
      <div className='row'>
        <div className="col-md-3">
          <div className='form-group'>
            <label htmlFor="institucion_correo">Correo</label>
            <input className='form-control'
              type="text"
              name="institucion[correo]"
              id="institucion_correo"
              defaultValue={institucion.correo}
            />
          </div>
        </div>

        <div className="col-md-3">
          <div className='form-group'>
            <label htmlFor="institucion_telefono">Telefono</label>
            <input className='form-control'
              type="text"
              name="institucion[telefono]"
              id="institucion_telefono"
              defaultValue={institucion.telefono}
            />
          </div>
        </div>

        <div className="col-md-3">
          <div className='form-group'>
            <label htmlFor="institucion_fax">Fax</label>
            <input className='form-control'
              type="text"
              name="institucion[fax]"
              id="institucion_fax"
              defaultValue={institucion.fax}
            />
          </div>
        </div>
      </div>

      <div className='row'>
        <div className="col-md-9">
          <div className='form-group'>
            <label htmlFor="institucion_sitioWeb">Página web</label>
            <input className='form-control'
              type="text"
              name="institucion[sitioWeb]"
              id="institucion_sitioWeb"
              defaultValue={institucion.sitioWeb}
            />
          </div>
        </div>
      </div>

      <div className='row'>
        <div className="col-md-9">
          <label htmlFor="institucion_cedulaFile_file">Archivo</label>
          <input
            type="file"
            name="institucion[cedulaFile][file]"
            id="institucion_cedulaFile_file"
          />
        
      
          <div className="hidden">
            <input
              type="checkbox"
              name="institucion[cedulaFile][delete]"
              id="institucion_cedulaFile_delete"
              checked={true}
            />
          </div>
        </div>

        <div className='col-md-3'>
          <button type="submit">Guardar</button>
        </div>
      </div>
    </form>
  )
}

const ListConvenios = ({ convenios }) => {
  return(
    <table className='table table-bordered'>
      <thead className='headers'>
          <tr>
              <th>Número</th>
              <th>Grado</th>
              <th>Ciclo</th> 
              <th>Carrera</th> 
              <th>Vigencia</th> 
          </tr>
      </thead>
      <tbody>
      {
          convenios.map((item) => {
            console.log(item);
          return <tr style={{ backgroundColor: item.convenio.label}}>
              <td>{item.id}</td>
              <td>{item.convenio.carrera.nivelAcademico.nombre}</td>
              <td>{item.cicloAcademico.nombre}</td>
              <td>{item.convenio.carrera.nombre}</td>
              <td>{item.convenio.vigencia}</td>
              
          </tr>
      })}
      </tbody>
  </table>  
  )}

export default ValidarInfo

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ValidarInfo
      institucion={window.INSTITUCION_PROP}
    />,
    document.getElementById('validar-info')
  )

  ReactDOM.render(
    <ListConvenios
      convenios={window.CONVENIOS_PROP}
    />,
    document.getElementById('lista-convenio')
  )
})

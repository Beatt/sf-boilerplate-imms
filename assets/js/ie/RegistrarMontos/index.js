import React from 'react';
import ReactDOM from 'react-dom'
import camelcaseKeys from 'camelcase-keys'

const Registrar = (
  {
    autorizados,
    solicitudId,
    institucion,
    carreras,
    institucionId
  }) => {

    let acceso = false;

    if(autorizados[0].autorizados != 0)
      acceso = true;
    
  return (
    <>
    {
       acceso ? 
    <form
      action={`/instituciones/${institucionId}/solicitudes/${solicitudId.id}/registrar`}
      method="post"
      encType='multipart/form-data'
    >

     

      <div className='row'>
        <div className="col-md-12 mb-10">
          <p>Se autorizaron {autorizados[0].autorizados} Campos Clínicos para las carreras de&nbsp;
            <strong>{carreras.map(carrera => carrera.nombre).join(', ')}</strong>
          </p>
        </div>

        <div className="col-md-12 mb-10">
          <div className="row">
            <div className="col-md-8">
              <p>Cargue el oficio que contenga los montos de inscripción de todas las carreras que comprenden su solicitud de campos clínicos </p>
            </div>
            <div className="col-md-4">
              <input
                type="file"
                name='solicitud_validacion_montos[urlArchivoFile]'
                required={true}
              />
            </div>
          </div>
        </div>

        <div className="col-md-12 mb-10">
          <p>Ingrese los montos correspondientes a cada carrera de su solicitud</p>
        </div>

        <div className="col-md-12 mb-10">
          <div className="panel panel-default">
            <div className="panel-body">
              <table className='table'>
                <thead className='headers'>
                <tr>
                  <th>Nivel Académico</th>
                  <th>Carrera</th>
                  <th>Inscripción</th>
                  <th>Colegiatura</th>
                </tr>
                </thead>
                <tbody>
                {
                  carreras.map((carrera, index) =>
                    <tr key={index}>
                      <td>{carrera.nivelAcademico}</td>
                      <td>{carrera.nombre}</td>
                      <td className='hidden'>
                        <input
                          className='form-control'
                          type="number"
                          min={1}
                          step={0.01}
                          defaultValue={carrera.id}
                          name={`solicitud_validacion_montos[montosCarreras][${index}][carrera]`}
                        />
                      </td>
                      <td className="form-inline">
                        <div className="form-group">
                          <div className="input-group">
                            <div class="input-group-addon">$</div>
                            <input
                              className='form-control'
                              type="number"
                              min={1}
                              step={0.01}
                              name={`solicitud_validacion_montos[montosCarreras][${index}][montoInscripcion]`}
                              id="solicitud_validacion_montos_montosCarreras_${index}_montoInscripcion"
                              defaultValue={carrera.montoInscripcion}
                              required={true}
                            />
                            </div>
                          </div>
                      </td>
                      <td className="form-inline">
                      <div className="form-group">
                          <div className="input-group">
                            <div class="input-group-addon">$</div>
                            <input
                              className='form-control'
                              type="text"
                              name={`solicitud_validacion_montos[montosCarreras][${index}][montoColegiatura]`}
                              id="solicitud_validacion_montos_montosCarreras_${index}_montoColegiatura"
                              defaultValue={carrera.montoColegiatura}
                              required={true}
                            />
                          </div>
                        </div>
                      </td>
                    </tr>
                  )
                }
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div className='col-md-12 mb-20'>
          <p>
            La institución educativa
            <span className='text-bold'>{institucion}</span>, confirma que el oficion adjunto, contiene el monto correspondiente a los montos de la colegiatura e inscripción por cada una de las carreras mencionadas anteriormente&nbsp;
            <label htmlFor="solicitud_validacion_montos_confirmacionOficioAdjunto">
              <input
                type="checkbox"
                id='solicitud_validacion_montos_confirmacionOficioAdjunto'
                name='solicitud_validacion_montos[confirmacionOficioAdjunto]'
                required={true}
              />&nbsp;Confirmo información
            </label>
          </p>
        </div>
      </div>
      <div className="col-md-12">
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
    </form>
      :
    <div className="mt-20"><h1><center>Lo sentimos, no tiene campos clínicos autorizados</center></h1></div>
    }
    </>
  )
}


ReactDOM.render(
  <Registrar
    autorizados={window.AUTORIZADOS_PROP}
    institucion={window.INSTITUCION_PROP}
    carreras={camelcaseKeys(window.CARRERAS_PROP)}
    solicitudId={window.SOLICITUD_ID_PROP}
    institucionId={window.INSTITUCION_ID_PROP}
  />,
  document.getElementById('registrar-montos-component')
);

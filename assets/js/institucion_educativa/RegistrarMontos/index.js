import React from 'react';
import ReactDOM from 'react-dom'
import camelcaseKeys from 'camelcase-keys'
import { SOLICITUD } from "../../constants";

const Registrar = (
  {
    autorizados,
    solicitudId,
    institucion,
    carreras,
    institucionId,
    url
  }) => {

    {console.log(solicitudId)}
  return (
    <form
      action={`/instituciones/${institucionId}/solicitudes/${solicitudId.id}/registrar`}
      method="post"
      encType='multipart/form-data'
    >
      <div className='row'>
        <div className="col-md-12 mt-10 mb-10">
          <p>Se autorizaron {autorizados[0].autorizados} Campos Clínicos para las carreras de&nbsp;
            <strong>{carreras.map(carrera => carrera.nombre).join(', ')}</strong>
          </p>
        </div>

        {
          solicitudId.estatus === SOLICITUD.MONTOS_INCORRECTOS_CAME  ? 

          <div className="col-md-12 mt-10 mb-10">
            <p className="error">Por favor, ingrese la información correcta correspondiente a los montos de inscripción y colegiatura</p>

            <p className="mt-10 mb-10"><strong>Observaciones</strong></p>

            <div className="mt-10 mb-10 observaciones"><p>El oficio adjunto no especifica los montos de inscripción para la carrera Trabajo Sociial</p></div>
          </div> :
          ""
        }

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
                          type="text"
                          defaultValue={carrera.id}
                          name={`solicitud_validacion_montos[montosCarreras][${index}][carrera]`}
                        />
                      </td>
                      <td>
                      <div className="form-group">
                        <input
                          className='form-control'
                          type="text"
                          name={`solicitud_validacion_montos[montosCarreras][${index}][montoInscripcion]`}
                          id="solicitud_validacion_montos_montosCarreras_${index}_montoInscripcion"
                          defaultValue={carrera.montoInscripcion}
                          required={true}
                          pattern="^\d+(\.\d+)*$"
                          placeholder="Ingrese solo numeros"
                        />
                        </div>
                      </td>
                      <td>
                      <div className="form-group">
                        <input
                          className='form-control'
                          type="text"
                          name={`solicitud_validacion_montos[montosCarreras][${index}][montoColegiatura]`}
                          id="solicitud_validacion_montos_montosCarreras_${index}_montoColegiatura"
                          defaultValue={carrera.montoColegiatura}
                          required={true}
                          pattern="^\d+(\.\d+)*$"
                          placeholder="Ingrese solo numeros"
                        />
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
  )
}


ReactDOM.render(
  <Registrar
    autorizados={window.AUTORIZADOS_PROP}
    institucion={window.INSTITUCION_PROP}
    carreras={camelcaseKeys(window.CARRERAS_PROP)}
    solicitudId={window.SOLICITUD_ID_PROP}
    institucionId={window.INSTITUCION_ID_PROP}
    url={window.ROUTE_PROP}
  />,
  document.getElementById('registrar-component')
);

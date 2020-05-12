import React from 'react';
import ReactDOM from 'react-dom'

const Registrar = (
  {
    autorizados,
    solicitudId,
    institucion,
    carreras,
    institucionId
  }) => {
  return (
    <div className='row'>
      <div className="col-md-12 mt-10">
        <p>Se autorizaron {autorizados} Campos Clínicos para las carreras de

          {
            carreras.map((item) => <strong> {item.nombre}, </strong>)
          }

        </p>
      </div>

      <div className="col-md-8 mt-10">
        <p>Cargue el oficio que contenga los montos de inscripción de todas las carreras que comprenden su solicitud de
          campos clínicos </p>
      </div>

      <div className="col-md-4 mt-10">

      </div>

      <div className="col-md-12 mt-10">
        <p>Ingrese los montos correspondientes a cada carrera de su solicitud</p>
      </div>


      <form
        action={`/instituciones/${institucionId}/solicitudes/${solicitudId}/registrar`}
        method="post"
      >
        <div className="col-md-12 mt-20">
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
                        <input
                          className='form-control'
                          type="text"
                          name={`solicitud_validacion_montos[montosCarreras][${index}][montoInscripcion]`}
                          id="montosCarrera_montoInscripcion"
                          defaultValue={carrera.montoInscripcion}
                          required={true}
                        />
                      </td>
                      <td>
                        <input
                          className='form-control'
                          type="text"
                          name={`solicitud_validacion_montos[montosCarreras][${index}][montoColegiatura]`}
                          id="montosCarrera_montoColegiatura"
                          defaultValue={carrera.montoColegiatura}
                          required={true}
                        />
                      </td>
                    </tr>
                  )
                }
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <button type="submit">Enviar</button>
      </form>

      <div className='col-md-12 mt-10'>
        <p>La institución educativa <span className='text-bold'>{institucion}</span>, confirma que el oficion adjunto,
          contiene el monto correspondiente a
          los montos de la colegiatura e inscripción por cada una de las carreras mencionadas anteriormente</p>
      </div>


    </div>
  )


}


ReactDOM.render(
  <Registrar
    autorizados={window.AUTORIZADOS_PROP}
    institucion={window.INSTITUCION_PROP}
    carreras={window.CARRERAS_PROP}
    solicitudId={window.SOLICITUD_ID_PROP}
    institucionId={window.INSTITUCION_ID_PROP}
  />,
  document.getElementById('registrar-component')
);

import React from 'react';
import ReactDOM from 'react-dom'
import camelcaseKeys from 'camelcase-keys'
import { SOLICITUD } from "../../constants";
import {getSchemeAndHttpHost} from "../../utils";

const Registrar = (
  {
    autorizados,
    solicitudId,
    institucion,
    carreras,
    errors,
    route
  }) => {

  const [executing, setExecuting] = React.useState(false);
  const [inputList, setInputList] = React.useState([{ firstName: "", lastName: "" }]);


  let acceso = false;
  let editar = false;

  const handleCurrency = (e) => {
    let valPrev = e.value.toString().replace(",", "");
    if( valPrev != '' && !isNaN(valPrev)){
      valPrev= parseFloat(valPrev).toFixed(2);
      e.value = valPrev.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }else{
      e.value = '';
    }
  };

  // handle click event of the Remove button
  const handleRemoveClick = index => {
    const list = [...inputList];
    list.splice(index, 1);
    setInputList(list);
  };

  // handle click event of the Add button
  const handleAddClick = () => {
    setInputList([...inputList, { firstName: "", lastName: "" }]);
  };


  const formSubmit = (e) =>{
    try{
      var tag = document.getElementsByClassName('solicitud_validacion_montos_inscripcion');

      for (var i = 0; i < tag.length; i++) {
        tag[i].value = tag[i].value.replace(/,/g, '');
      }
      setExecuting(true);
    }catch(error){
      e.preventDefault();
    }
  };


  if (autorizados[0].autorizados !== 0) acceso = true;
  if (route == "ie#corregir_montos") editar = true;
  return (
    <>
      {
        acceso ?
          <form
            action={`${getSchemeAndHttpHost()}/ie/solicitudes/${solicitudId.id}/registrar-montos`}
            method="post"
            encType='multipart/form-data'
            onSubmit= {formSubmit}
          >
            <div className='row'>
              <div className="col-md-12 mb-10 mt-10">
                <p>Se autorizaron {autorizados[0].autorizados} Campos Clínicos para las carreras de&nbsp;
                  <strong>{carreras.map(carrera => carrera.nombre).join(', ')}</strong>
                </p>
              </div>

              {
                editar ?

                <div>
                  <div className="col-md-12 bm-10 tm-10">
                    <span className="error-message mb-10 mt-10"><strong>Por favor, ingrese la información correcta correspondiente a los montos de inscripción y de colegiaturas</strong></span>
                  </div>

                  <div className="col-md-12 bm-10 mt-10">
                    <p>Observaciones:</p>
                    <p className="bm-10 mt-10 background">{solicitudId.observaciones}</p>
                  </div>
                </div>

                :

                ''
              }



              <div className="col-md-12 mb-10 mt-10">
                <div className="row">
                  <div className="col-md-8">
                    <p>
                      Adjunte documento oficial que contenga los importes de inscripción y colegiaturas de todas
                      las carreras que comprenden su solicitud de campos clínicos
                      y oficio detallado de los importes de beca que aplique.
                    </p>
                  </div>
                  <div className="col-md-4">
                    <input
                      type="file"
                      name='solicitud_validacion_montos[urlArchivoFile]'
                      required={true}
                    />
                    { errors && <span className='error-message'>{ errors['urlArchivoFile'] }</span> }
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
                                  <div className="input-group-addon">$</div>
                                  <input
                                    className='form-control'
                                    type="text"
                                    min={1}
                                    step={0.01}
                                    name={`solicitud_validacion_montos[montosCarreras][${index}][montoInscripcion]`}
                                    className="form-control solicitud_validacion_montos_inscripcion"
                                    defaultValue={carrera.montoInscripcion}
                                    required={true}
                                    onBlur={e => handleCurrency(e.target)}
                                  />
                                </div>
                              </div>
                            </td>
                            <td className="form-inline">
                              <div className="form-group">
                                <div className="input-group">
                                  <div className="input-group-addon">$</div>
                                  <input
                                    className='form-control'
                                    type="text"
                                    name={`solicitud_validacion_montos[montosCarreras][${index}][montoColegiatura]`}
                                    id="solicitud_validacion_montos_inscripcion"
                                    className="form-control solicitud_validacion_montos_inscripcion"
                                    defaultValue={carrera.montoColegiatura}
                                    required={true}
                                    onBlur={e => handleCurrency(e.target)}
                                  />
                                </div>
                              </div>
                            </td>
                            <td>
                              <div>
                                <button
                                    onClick={handleAddClick}
                                >Agregar descuento</button>
                                {inputList.map((x, i) => {
                                  return (
                                      <div className="box">
                                        <input
                                            name="firstName"
                                            placeholder="% descuento Inscripción"
                                            value={x.firstName}
                                        />
                                        <input
                                            className="ml10"
                                            name="lastName"
                                            placeholder="% descuento Colegiatura"
                                            value={x.lastName}
                                        />
                                        <div className="btn-box">
                                          {inputList.length !== 0 &&
                                          <button
                                              className="mr10"
                                              onClick={() => handleRemoveClick(i)}
                                          >Eliminar</button>}
                                        </div>
                                      </div>
                                  );
                                })}
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
                  La institución  &nbsp;
                  <span className='text-bold'>{institucion}</span>
                  valida  que la información capturada y archivos adjuntos corresponden a su solicitud.
                  &nbsp;&nbsp;
                  <label htmlFor="solicitud_validacion_montos_confirmacionOficioAdjunto">
                    <input
                      type="checkbox"
                      id='solicitud_validacion_montos_confirmacionOficioAdjunto'
                      name='solicitud_validacion_montos[confirmacionOficioAdjunto]'
                      required={true}
                    />&nbsp;Acepto
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
                    disabled={executing}
                  >
                    Guardar
                  </button>
                </div>
              </div>
            </div>
          </form>
          :
          <div className="mt-20">
            <h1>
              <center>Lo sentimos, no tiene campos clínicos autorizados</center>
            </h1>
          </div>
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
    errors={window.ERRORS_PROP}
    route={window.ROUTE_PROP}
  />,
  document.getElementById('registrar-montos-component')
);

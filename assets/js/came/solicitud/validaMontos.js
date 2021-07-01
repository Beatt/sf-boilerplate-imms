import * as React from 'react'
import Loader from "../../components/Loader/Loader";
import {getSchemeAndHttpHost} from "../../utils";
import RegistrarDescuentos from "../../ie/RegistrarMontos/Descuentos";
import {Fragment} from "react";
import './styles/tables.scss';

const SolicitudValidaMontos = (props) => {

    const [isLoading, setIsLoading] = React.useState(false);
    const [validos, setValidos] = React.useState(false);
    const [camposClinicos, setCamposClinicos] = React.useState(props.solicitud.camposClinicos);
    const [alert, setAlert] = React.useState({});
    const [errores, setErrores] = React.useState({});
    const [descValidos, setDescValidos] = React.useState(true);
    const [descValidosCC, setDescValidosCC] = React.useState(
      props.solicitud.camposClinicos.reduce((acc, elem) => { acc.push({id: elem.id, validate: true}); return acc; },
        [])
    );

    const callbackIsLoading = (value) => {
        setIsLoading(value);
    }

    const callbackDescuentos = (i, value, idCampo, val) => {
      const indexCC = descValidosCC.findIndex(item => {return item.id === idCampo});
      descValidosCC[indexCC] = {id: idCampo, validate: val};
      setDescValidosCC(descValidosCC);
      setDescValidos(descValidosCC.reduce((acc, elem) => {return acc && elem.validate}, true));

      camposClinicos[i].montoCarrera.descuentos = value;
      setCamposClinicos(Object.assign([], camposClinicos));
    }

    const handleSolicitudValidaMontos = (event) => {
        event.preventDefault();
        if(!descValidos) return;
        setIsLoading(true);
        let data = new FormData();
        camposClinicos.map((campo, i) => {
           data.append(`solicitud[campo_${campo.id}][observaciones]`, campo.new_observaciones ? campo.new_observaciones : '' );
           data.append(`solicitud[campo_${campo.id}][montoCarrera][montoInscripcion]`, campo.montoCarrera.montoInscripcion);
           data.append(`solicitud[campo_${campo.id}][montoCarrera][montoColegiatura]`, campo.montoCarrera.montoColegiatura);

            campo.montoCarrera.descuentos.map((desc, iDesc) => {
                 data.append(`solicitud[campo_${campo.id}][montoCarrera][descuentos][${iDesc}][numAlumnos]`, desc.numAlumnos);
                 data.append(`solicitud[campo_${campo.id}][montoCarrera][descuentos][${iDesc}][descuentoInscripcion]`, desc.descuentoInscripcion);
                 data.append(`solicitud[campo_${campo.id}][montoCarrera][descuentos][${iDesc}][descuentoColegiatura]`, desc.descuentoColegiatura);
           });
        });

        if(validos.toString() === (1).toString()){
            data.append('solicitud[validado]', validos);
        }

        fetch(`${getSchemeAndHttpHost()}/came/api/solicitud/validar_montos/${props.solicitud.id}` , {
            method: 'post',
            body: data
        }).then(response => {
            return response.json()
        }, error => {console.error(error)
        }).then(json => {
            if (json.errors) {
                setErrores(json.errors);
            }
            setAlert(Object.assign(alert, {
                show: true,
                message: json.message,
                type: (json.status ? 'success' : 'danger')
            }))
            if(json.status){
                new Promise((resolve, reject) => {
                   setTimeout(() => {
                       resolve()
                   }, 250)
                }).then(() => {
                    document.location.href = `${getSchemeAndHttpHost()}/came/solicitud`;
                });
            }
        }).finally(() => {
            setIsLoading(false);
        })
    }

    return (
        <>
            <Loader show={isLoading}/>
            <div className="col-md-12">
                <div className={`alert alert-${alert.type} `}
                     style={{display: (alert.show ? 'block' : 'none')}}>
                    <a className="close" onClick={e => setAlert({})}>&times;</a>
                    {alert.message}
                </div>
            </div>

            <div className="col-md-12">
                <p><strong>No. de Solicitud:</strong> {props.solicitud.noSolicitud}</p>
            </div>
            <div className="col-md-12">
                <p><strong>Estado:</strong> {props.solicitud.estatusCameFormatted}</p>
            </div>
            <div className="col-md-12">
                <p><strong>Insitución Educativa:</strong> {props.solicitud.institucion.nombre}</p>
            </div>
            <div className="col-md-12">
                <p>Oficio: <a href={`${getSchemeAndHttpHost()}/came/solicitud/${props.solicitud.id}/oficio`} target={'_blank'}>{props.solicitud.urlArchivo}</a></p>
            </div>
            <div className="col-md-12">
                <p><strong>Por favor valide los montos que se muestran a continuación, deben coincidir con los reportados en el oficio</strong></p>
            </div>
            <form onSubmit={handleSolicitudValidaMontos}>
                <div className="col-md-12">
                    <table className="table">
                        <thead>
                        <tr>
                            <th>Carrera</th>
                            <th>Período</th>
                            <th>Sede</th>
                            <th>Inscripción</th>
                            <th>Colegiatura</th>
                        </tr>
                        </thead>
                        <tbody>
                        {camposClinicos.map((campo, i) =>{
                            return (
                              <Fragment key={campo.montoCarrera.id}>
                                <tr key={campo.montoCarrera.id}>
                                    <td>{campo.montoCarrera.carrera.nivelAcademico.nombre} - {campo.montoCarrera.carrera.nombre}</td>
                                    <td>
                                        <div>{campo.displayFechaInicial}-{campo.displayFechaFinal}
                                            <br />
                                            {campo.lugaresAutorizados} lugares autorizados
                                        </div>
                                    </td>
                                    <td>{campo.unidad.nombre}</td>
                                    <td>
                                        <div className="input-group col-md-8">
                                            <span className="input-group-addon">$</span>
                                            <input className="form-control"
                                                   type="number" value={camposClinicos[i].montoCarrera.montoInscripcion}
                                                   min={0}
                                                   step="0.01"
                                                   required={true}
                                                   onChange={e => { camposClinicos[i].montoCarrera.montoInscripcion = e.target.value; setCamposClinicos(Object.assign([], camposClinicos))}}
                                                   />
                                        </div>
                                    </td>
                                    <td>
                                        <div className="input-group col-md-8">
                                            <span className="input-group-addon">$</span>
                                            <input className="form-control"
                                                   type="number" value={camposClinicos[i].montoCarrera.montoColegiatura}
                                                   min={0}
                                                   step="0.01"
                                                   required={true}
                                                   onChange={e => {camposClinicos[i].montoCarrera.montoColegiatura = e.target.value; setCamposClinicos(Object.assign([], camposClinicos))}}
                                        />
                                        </div>
                                    </td>
                                </tr>
                                { campo.observaciones ?
                                <tr className={'desc'}>
                                  <td colSpan={5}>
                                    Revisión anterior:
                                    <p className='background' > {campo.observaciones} </p>
                                  </td>
                                </tr> :
                                  null
                                }
                                <tr className={'without-border-top'}>
                                    <td colSpan={5}>
                                        <RegistrarDescuentos
                                          prefixName={`solicitud_validacion_montos[montosCarreras][${i}][descuentos]`}
                                          campo={campo}
                                          carrera={campo.montoCarrera.carrera}
                                          campos={props.solicitud.camposClinicos}
                                          descuentos={campo.montoCarrera.descuentos}
                                          onChange={callbackDescuentos}
                                          indexMonto={i}
                                        />
                                    </td>
                                </tr>
                                <tr className={'without-border-top'}>
                                  <td colSpan={5}>
                                    <div className="col-md-10">
                                      <div className={`form-group ${errores.observaciones ? 'has-error has-feedback' : ''}`}>
                                        <label htmlFor="observaciones_solicitud">Observaciones</label>
                                        <textarea
                                                  className={'form-control'}
                                                  placeholder={'Observaciones'}
                                                  onChange={e => { camposClinicos[i].new_observaciones = e.target.value; setCamposClinicos(Object.assign([], camposClinicos))}}
                                        />
                                        <span className="help-block">{errores.observaciones ? errores.observaciones[0] : ''}</span>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                                </Fragment>
                            )
                        })}
                        </tbody>
                    </table>
                </div>
                <div className="col-md-12"/>
                <div className="col-md-3">
                    <div className={`form-group ${errores.validado ? 'has-error has-feedback' : ''}`}>
                        <label htmlFor="validos_solicitud">¿Todos los montos corresponden?</label>
                        <select id="validos_solicitud" className={'form-control'}
                                required={true} onChange={e => setValidos(e.target.value)}>
                            <option value="">Seleccionar ...</option>
                            <option value={1}>Si</option>
                            <option value={0}>No</option>
                        </select>
                        <span className="help-block">{errores.validado ? errores.validado[0] : ''}</span>
                    </div>
                </div>
                <div className="col-md-8"/>
                <div className="col-md-4">
                    <label htmlFor="btn_solicitud">&#160;</label>
                    <button id="btn_solicitud" className={`form-control btn btn-primary ${descValidos ? ' ' : 'dsabled'}`}
                            disabled={descValidos ? '' : 'dsabled'}>Guardar</button>
                </div>
            </form>
        </>
    )
}

export default SolicitudValidaMontos;
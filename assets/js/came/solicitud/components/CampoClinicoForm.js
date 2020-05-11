import * as React from 'react'

const CampoClinicoForm = (props) => {

    const [isLoading, setIsLoading] = React.useState(true);
    const [solicitud, setSolicitud] = React.useState(props.solicitud ? props.solicitud : null);
    const [convenio, setConvenio] = React.useState(null);
    const [fechaInicial, setFechaInicial] = React.useState(new Date());
    const [fechaFinal, setFechaFinal] = React.useState(new Date());
    const [horario, setHorario] = React.useState('');
    const [lugaresAutorizados, setLugaresAutorizados] = React.useState(0);
    const [lugaresSolicitados, setLugaresSolicitados] = React.useState(0);
    const [promocion, setPromocion] = React.useState('');
    const [unidad, setUnidad] = React.useState('');
    const [asignatura, setAsignatura] = React.useState('');

    const [errores, setErrores] = React.useState({});
    const [alert, setAlert] = React.useState({});

    const initConvenios = (convenios) => {
        let data = [];
        for (const i of convenios) {
            if (i.carrera && i.cicloAcademico) {
                data.push(i);
            }
        }
        return data;
    }

    const getConveniosActivos = (convenios) => {
        let data = [];
        for (const i of convenios) {
            if (i.carrera && i.cicloAcademico && i.label.toString() !== 'red') {
                data.push(i);
            }
        }
        return data;
    }

    const handleSubmit = (event) => {
        event.preventDefault();
        setErrores({});
        setAlert({});
        if (isFormValid()) {
            props.callbackIsLoading(true);
            if (solicitud) {
                storeCampoClinico(solicitud);
            } else {
                storeSolicitud().then(solicitud => {
                    setSolicitud(solicitud)
                    props.callbackSolicitud(solicitud);
                    storeCampoClinico(solicitud)
                });
            }
        }

    }

    const storeSolicitud = () => {
        return new Promise((resolve, reject) => {
            fetch('/api/solicitud', {
                method: 'post'
            }).then(response => {
                return response.json();
            }, error => {
                reject(error);
            }).then(json => {
                resolve(json.data);
            });
        });
    }

    const handleConvenioEvent = (selected) => {
        let convenio = null;
        for (const i of props.convenios) {
            if ((i.id).toString() === (selected).toString()) {
                convenio = i;
            }
        }
        setConvenio(convenio);
    }

    const isFormValid = () => {
        let result = true;
        const fechaI = new Date(fechaInicial);
        const fechaF = new Date(fechaFinal);
        if (fechaI >= fechaF) {
            setAlert({show: true, type: 'alert', message: 'Fechas incorrectas'});
            setErrores({
                fechaInicial: ['Fecha Inicial debe ser menor a Fecha Final'],
                fechaFinal: ['Fecha Inicial debe ser mayor a Fecha Inicial']
            });
            result = false;
        }
        return result;
    }

    const storeCampoClinico = (solicitud) => {

        let data = new FormData();
        const fechaI = new Date(fechaInicial);
        const fechaF = new Date(fechaFinal);
        data.append('campo_clinico[solicitud]', solicitud.id);
        data.append('campo_clinico[convenio]', convenio.id);
        data.append('campo_clinico[unidad]', unidad.id);
        data.append('campo_clinico[fechaInicial][year]', fechaI.getFullYear());
        data.append('campo_clinico[fechaInicial][month]', fechaI.getMonth() + 1);
        data.append('campo_clinico[fechaInicial][day]', fechaI.getDate());
        data.append('campo_clinico[horario]', horario);
        data.append('campo_clinico[lugaresSolicitados]', lugaresSolicitados);
        data.append('campo_clinico[lugaresAutorizados]', lugaresAutorizados);
        data.append('campo_clinico[fechaFinal][year]', fechaF.getFullYear());
        data.append('campo_clinico[fechaFinal][month]', fechaF.getMonth() + 1);
        data.append('campo_clinico[fechaFinal][day]', fechaF.getDate());
        data.append('campo_clinico[asignatura]', asignatura);

        fetch('/api/came/campo_clinico', {
            method: 'post',
            body: data
        }).then(response => {
            props.callbackIsLoading(false);
            return response.json()
        }, error => {
            props.callbackIsLoading(false);
        }).then(json => {
            if (json.errors) {
                setErrores(json.errors);
            } else {
                props.callbackCampoClinico(json.data);
                setConvenio(null);
                setFechaInicial('');
                setFechaFinal('');
                setHorario('');
                setLugaresSolicitados(0);
                setLugaresAutorizados(0);
                setPromocion('');
                setUnidad('');
                setAsignatura('');
            }
            setAlert(Object.assign(alert, {
                show: true,
                message: json.message,
                type: (json.status ? 'success' : 'danger')
            }))
        });

    }

    const findUnidad = (value) => {
        let unidad = null;
        for (const i of props.unidades) {
            if ((i.id).toString() === value.toString()) {
                unidad = i;
            }
        }
        return unidad;
    }

    return (
        <>
            <div className="row">
                <div className="col-md-12">
                    <h3>Ingrese la información correspondiente a la carrera solicitada para campo clínico</h3>
                </div>
            </div>

            <div className="col-md-12">
                <div className={`alert alert-${alert.type} `} style={{display: (alert.show ? 'block' : 'none')}}>
                    <a className="close" onClick={e => setAlert({})}>&times;</a>
                    {alert.message}
                </div>
            </div>

            <form onSubmit={handleSubmit}>
                <div className="row">
                    <div className="col-md-12">
                        <div className={`form-group ${errores.convenio ? 'has-error has-feedback' : ''}`}>
                            <select name="convenio" id="campo_convenio" className={'form-control'}
                                    value={convenio ? convenio.id : ''}
                                    required={true} onChange={e => handleConvenioEvent(e.target.value)}>
                                <option value="">Seleccionar ...</option>
                                {getConveniosActivos(props.convenios).map(c => {
                                    return (
                                        <option key={c.id}
                                                value={c.id}>{c.cicloAcademico.nombre} - {c.carrera.nivelAcademico.nombre} - {c.carrera.nombre}</option>
                                    )
                                })}
                            </select>
                            <span className="help-block">{errores.convenio ? errores.convenio[0] : ''}</span>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <label htmlFor="fecha_inicial">Periodo</label>
                    </div>
                </div>

                <div className="row">
                    <div className="col-md-3">
                        <div className={`form-group ${errores.fechaInicial ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="fecha_inicial">Inicio</label>
                            <input id="fecha_inicial" type="date" value={fechaInicial} className={'form-control'}
                                   onChange={e => setFechaInicial(e.target.value)} required={true}/>
                            <span className="help-block">{errores.fechaInicial ? errores.fechaInicial[0] : ''}</span>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <div className={`form-group ${errores.fechaFinal ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="fecha_final">Fin</label>
                            <input id="fecha_final" type="date" value={fechaFinal} className={'form-control'}
                                   onChange={e => setFechaFinal(e.target.value)} required={true}/>
                            <span className="help-block">{errores.fechaFinal ? errores.fechaFinal[0] : ''}</span>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <div className={`form-group ${errores.horario ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="horario">Horario del campo clínico (Opcional)</label>
                            <input id="horario" className={'form-control'}
                                   type="text" value={horario} onChange={e => setHorario(e.target.value)}/>
                            <span className="help-block">{errores.horario ? errores.horario[0] : ''}</span>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <div className={`form-group ${errores.promocion ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="promocion">Promoción de Inicio [solo para Internado]</label>
                            <input id="promocion" className={'form-control'}
                                   disabled={!convenio || (convenio && convenio.cicloAcademico.id !== 2)}
                                   type="text" value={promocion} onChange={e => setPromocion(e.target.value)}/>
                            <span className="help-block">{errores.promocion ? errores.promocion[0] : ''}</span>
                        </div>
                    </div>
                </div>

                <div className="row">
                    <div className="col-md-12">
                        <div className={`form-group ${errores.unidad ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="unidad">Unidad Sede</label>
                            <select name="unidad" id="unidad"
                                    className={'form-control'}
                                    value={unidad ? unidad.id : ''}
                                    required={true} onChange={e => setUnidad(findUnidad(e.target.value))}>
                                <option value="">Seleccionar</option>
                                {props.unidades.map(unidad => {
                                    return (
                                        <option key={unidad.id} value={unidad.id}>{unidad.nombre}</option>
                                    )
                                })}
                            </select>
                            <span className="help-block">{errores.unidad ? errores.unidad[0] : ''}</span>
                        </div>
                    </div>
                </div>

                <div className="row">
                    <div className="col-md-3">
                        <div className={`form-group ${errores.lugaresSolicitados ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="lugaresSolicitados">No. de lugares solicitados</label>
                            <input id={'lugaresSolicitados'}
                                   type="number" value={lugaresSolicitados}
                                   min={0}
                                   className={'form-control'}
                                   onChange={e => setLugaresSolicitados(e.target.value)} required={true}/>
                            <span
                                className="help-block">{errores.lugaresSolicitados ? errores.lugaresSolicitados[0] : ''}</span>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <div className={`form-group ${errores.lugaresAutorizados ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="lugaresAutorizados">No. de lugares autorizados</label>
                            <input id={'lugaresAutorizados'}
                                   type="number" value={lugaresAutorizados}
                                   min={0}
                                   className={'form-control'}
                                   onChange={e => setLugaresAutorizados(e.target.value)} required={true}/>
                            <span
                                className="help-block">{errores.lugaresAutorizados ? errores.lugaresAutorizados[0] : ''}</span>
                        </div>
                    </div>
                    <div className="col-md-6">
                        <div className={`form-group ${errores.asignatura ? 'has-error has-feedback' : ''}`}>
                            <label htmlFor="asignatura">Asignatura</label>
                            <input type="text"
                                   id={'asignatura'}
                                   className={'form-control'} value={asignatura}
                                   onChange={e => setAsignatura(e.target.value)}/>
                            <span className="help-block">{errores.asignatura ? errores.asignatura[0] : ''}</span>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-6">
                        <label htmlFor="btn_campo_clinico">&#160;</label>
                        <button id="btn_campo_clinico" className={'form-control btn btn-primary'}>Guardar Campo
                            clínico
                        </button>
                    </div>
                </div>
            </form>
        </>
    );

}

export default CampoClinicoForm;
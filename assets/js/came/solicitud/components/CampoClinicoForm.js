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

    const initConvenios = (convenios) => {
        let data = [];
        for (const i of convenios) {
            if (i.carrera && i.cicloAcademico) {
                data.push(i);
            }
        }
        return data;
    }

    const handleSubmit = (event) => {
        event.preventDefault();
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

    const storeSolicitud = () => {
        return new Promise((resolve, reject) => {
            fetch('/api/solicitud', {
                method: 'post'
            }).then(response =>{
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
            console.error(error);
            props.callbackIsLoading(false);
        }).then(json => {
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
        });
    }

    const findUnidad = (value) => {
        let unidad = null;
        for(const i of props.unidades){
            if((i.id).toString() === value.toString()){
                unidad = i;
            }
        }
        return unidad;
    }

    return (
        <>
            <div className="col-md-12">
                <h3>Ingrese la información correspondiente a la carrera solicitada para campo clínico</h3>
            </div>
            <form onSubmit={handleSubmit}>
                <div className="col-md-12">
                    <select name="convenio" id="campo_convenio" className={'form-control'}
                            value={convenio? convenio.id : ''}
                            required={true} onChange={e => handleConvenioEvent(e.target.value)}>
                        <option value="">Seleccionar ...</option>
                        {initConvenios(props.convenios).map(c => {
                            return (
                                <option key={c.id}
                                        value={c.id}>{c.cicloAcademico.nombre} - {c.carrera.nivelAcademico.nombre} - {c.carrera.nombre}</option>
                            )
                        })}
                    </select>
                </div>
                <div className="col-md-12">
                    <label htmlFor="fecha_inicial">Periodo</label>
                </div>
                <div className="col-md-3">
                    <label htmlFor="fecha_inicial">Inicio</label>
                    <input id="fecha_inicial" type="date" value={fechaInicial} className={'form-control'}
                           onChange={e => setFechaInicial(e.target.value)} required={true}/>
                </div>
                <div className="col-md-3">
                    <label htmlFor="fecha_final">Fin</label>
                    <input id="fecha_final" type="date" value={fechaFinal} className={'form-control'}
                           onChange={e => setFechaFinal(e.target.value)} required={true}/>
                </div>
                <div className="col-md-3">
                    <label htmlFor="horario">Horario del campo clínico (Opcional)</label>
                    <input id="horario" className={'form-control'}
                           type="text" value={horario} onChange={e => setHorario(e.target.value)}/>
                </div>
                <div className="col-md-3">
                    <label htmlFor="promocion">Promoción de Inicio [solo para Internado]</label>
                    <input id="promocion" className={'form-control'}
                           disabled={!convenio || (convenio && convenio.cicloAcademico.id !== 2)}
                           type="text" value={promocion} onChange={e => setPromocion(e.target.value)}/>
                </div>
                <div className="col-md-12">
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
                </div>
                <div className="col-md-3">
                    <label htmlFor="lugaresSolicitados">No. de lugares solicitados</label>
                    <input id={'lugaresSolicitados'}
                           type="number" value={lugaresSolicitados}
                           min={0}
                           className={'form-control'}
                           onChange={e => setLugaresSolicitados(e.target.value)} required={true}/>
                </div>
                <div className="col-md-3">
                    <label htmlFor="lugaresAutorizados">No. de lugares autorizados</label>
                    <input id={'lugaresAutorizados'}
                           type="number" value={lugaresAutorizados}
                           min={0}
                           className={'form-control'}
                           onChange={e => setLugaresAutorizados(e.target.value)} required={true}/>
                </div>
                <div className="col-md-6">
                    <label htmlFor="">Asignatura</label>
                    <input type="text"
                           className={'form-control'} value={asignatura} onChange={e => setAsignatura(e.target.value)}/>
                </div>


                <div className="col-md-6">
                    <label htmlFor="btn_campo_clinico">&#160;</label>
                    <button id="btn_campo_clinico" className={'form-control btn btn-primary'}>Guardar Campo clínico</button>
                </div>
            </form>
        </>
    );

}

export default CampoClinicoForm;
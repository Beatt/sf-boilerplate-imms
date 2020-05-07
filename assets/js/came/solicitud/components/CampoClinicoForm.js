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

    const  initConvenios  = (convenios) => {
        let data = [];
        for(const i of convenios){
            if(i.carrera && i.cicloAcademico){
                data.push(i);
            }
        }
        return data;
    }

    const handleSubmit = (event) => {
        event.preventDefault();
        if(solicitud){
            storeCampoClinico();
        }else{
            storeSolicitud().then(solicitud => {
                setSolicitud(solicitud)
                storeCampoClinico()
            });
        }
    }

    const storeSolicitud = () => {
        return new Promise((resolve, reject) => {
            fetch('/api/solicitud', {
                method: 'post'
            }).then(response => response.json(), error => {
                reject(error);
            }).then(json => {
                resolve(json.data);
            });
        });
    }

    const handleConvenioEvent = (selected) => {
        console.log("seleccionado " + selected);
        let convenio = null;
        for(const i of props.convenios){
            if(i.id == selected){
                convenio = i;
            }
        }
        setConvenio(convenio);
    }

    const storeCampoClinico = () => {
        let data = new FormData();
        data.append('campo_clinico[solicitud]', solicitud.id);
        data.append('campo_clinico[convenio]', convenio.id);
        data.append('campo_clinico[unidad]', unidad.id);
        data.append('campo_clinico[fechaInicial][year]', fechaInicial.getFullYear());
        data.append('campo_clinico[fechaInicial][month]', fechaInicial.getMonth() + 1);
        data.append('campo_clinico[fechaInicial][day]', fechaInicial.getDate());
        data.append('campo_clinico[horario]', horario);
        data.append('campo_clinico[lugaresSolicitados]', lugaresSolicitados);
        data.append('campo_clinico[lugaresAutorizados]',  lugaresAutorizados);
        data.append('campo_clinico[fechaFinal][year]', fechaFinal.getFullYear());
        data.append('campo_clinico[fechaFinal][month]', fechaFinal.getMonth() + 1);
        data.append('campo_clinico[fechaFinal][day]', fechaFinal.getDate());

        fetch('/api/came/campo_clinico', {
            method: 'post',
            body: data
        }).then(response => response.json(), error => {
            reject(error);
        }).then(json => {
        });
    }

    return (
        <>
            <div className="col-md-12">
                <h3>Ingrese la información correspondiente a la carrera solicitada para campo clínico</h3>
            </div>
            <form onSubmit={handleSubmit}>
                <div className="col-md-12">
                    <select name="convenio" id="" required={true} onChange={e => handleConvenioEvent(e.target.value)}>
                        <option value="">Seleccionar ...</option>
                        {initConvenios(props.convenios).map(c => {
                            return (
                                <option key={c.id} value={c.id}>{c.cicloAcademico.nombre} - {c.carrera.nivelAcademico.nombre} - {c.carrera.nombre}</option>
                            )
                        })}
                    </select>
                </div>
                <div className="col-md-6">
                    <label htmlFor="">Periodo</label>
                    <input type="date" value={fechaInicial} onChange={e => setFechaInicial(e.target.value)} />
                    <input type="date" value={fechaFinal} onChange={e => setFechaFinal(e.target.value)} />
                </div>
                <div className="col-md-3">
                    <label htmlFor="">Horario del campo clínico (Opcional)</label>
                    <input type="text" value={horario} onChange={e => setHorario(e.target.value)} />
                </div>
                <div className="col-md-3">
                    <label htmlFor="">Promoción de Inicio [solo para Internado]</label>
                    <input type="text" value={promocion} onChange={e => setPromocion(e.target.value)}/>
                </div>
                <div className="col-md-12">
                    <label htmlFor="">Unidad Sede</label>
                    <select name="unidad" id="" required={true} onChange={e => setUnidad(props.unidades[e.target.value])}>
                        <option value="">Seleccionar</option>
                        {props.unidades.map(unidad => {
                            return (
                                <option key={unidad.id} value={unidad.id}>{unidad.nombre}</option>
                            )
                        })}
                    </select>
                </div>
                <div className="col-md-3">
                    <label htmlFor="">No. de lugares solicitados</label>
                    <input type="number" value={lugaresSolicitados} onChange={e => setLugaresSolicitados(e.target.value)}/>
                </div>
                <div className="col-md-3">
                    <label htmlFor="">No. de lugares autorizados</label>
                    <input type="number" value={lugaresAutorizados} onChange={e => setLugaresAutorizados(e.target.value)}/>
                </div>
                <div className="col-md-3">
                    <label htmlFor="">Asignatura</label>
                    <input type="text" value={asignatura} onChange={e => setAsignatura(e.target.value)}/>
                </div>

                <div className="col-md-6">
                    <button>Guardar Campo clínico</button>
                </div>
            </form>
        </>
    );

}

export default CampoClinicoForm;
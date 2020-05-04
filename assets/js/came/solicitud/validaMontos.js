import * as React from 'react'
import Loader from "../../components/Loader/Loader";

const SolicitudValidaMontos = (props) => {

    const [isLoading, setIsLoading] = React.useState(false);
    const [validos, setValidos] = React.useState(false);
    const [observaciones, setObservaciones] = React.useState('');
    const [montos, setMontos] = React.useState(props.solicitud.montos);
    const [alert, setAlert] = React.useState({});

    const callbackIsLoading = (value) => {
        setIsLoading(value);
    }

    const handleSolicitudValidaMontos = (event) => {
        event.preventDefault();
        setIsLoading(true);
        let data = new FormData();
        data.append('solicitud[observaciones]', observaciones);
        montos.map((monto, i) => {
           data.append(`solicitud[montos_pagos][${i}][montoInscripcion]`, monto.montoInscripcion);
            data.append(`solicitud[montos_pagos][${i}][montoColegiatura]`, monto.montoColegiatura);
        });
        if(validos.toString() === (1).toString()){
            data.append('solicitud[validado]', validos);
        }

        fetch('/api/solicitud/validar_montos/' + props.solicitud.id , {
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
                    document.location.href = '/solicitud';
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
                <p><strong>Por favor valide los que los montos que se muestran a continuación coincidan con los reportados en el oficio</strong></p>
            </div>
            <div className="col-md-12">
                <p>Oficio: {props.solicitud.documento}</p>
            </div>
            <div className="col-md-12">
                <p>Valide los montos correspondientes a cada carrera de su solicitud</p>
            </div>
            <form onSubmit={handleSolicitudValidaMontos}>
                <div className="col-md-3"/>
                <div className="col-md-6">
                    <table className="table">
                        <thead>
                        <tr>
                            <th>Nivel</th>
                            <th>Carrera</th>
                            <th>Inscripción</th>
                            <th>Colegiatura</th>
                        </tr>
                        </thead>
                        <tbody>
                        {montos.map((monto, i) =>{
                            return (
                                <tr key={monto.id}>
                                    <td>{monto.carrera.nivelAcademico.nombre}</td>
                                    <td>{monto.carrera.nombre}</td>
                                    <td><input type="number" value={montos[i].montoInscripcion}
                                               min={0}
                                               required={true}
                                               onChange={e => {montos[i].montoInscripcion = e.target.value; setMontos(Object.assign([], montos))}}/></td>
                                    <td><input type="number" value={montos[i].montoColegiatura}
                                               min={0}
                                               required={true}
                                               onChange={e => {montos[i].montoColegiatura = e.target.value; setMontos(Object.assign([], montos))}}/></td>
                                </tr>
                            )
                        })}
                        </tbody>
                    </table>
                </div>
                <div className="col-md-3"/>
                <div className="col-md-12"/>
                <div className="col-md-2">
                    <label htmlFor="validos_solicitud">¿Todos los montos corresponden?</label>
                    <select id="validos_solicitud" className={'form-control'}
                            required={true} onChange={e => setValidos(e.target.value)}>
                        <option value="">Seleccionar ...</option>
                        <option value={1}>Si</option>
                        <option value={0}>No</option>
                    </select>
                </div>
                <div className="col-md-12">
                    <label htmlFor="observaciones_solicitud">Observaciones</label>
                    <textarea className={'form-control'}
                              placeholder={'Observaciones'}
                              onChange={e => setObservaciones(e.target.value)}
                    />
                </div>
                <div className="col-md-8"/>
                <div className="col-md-4">
                    <label htmlFor="btn_solicitud">&#160;</label>
                    <button id="btn_solicitud" className={'form-control btn btn-primary'}>Guardar</button>
                </div>
            </form>
        </>
    )
}

export default SolicitudValidaMontos;
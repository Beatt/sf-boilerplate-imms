import * as React from 'react'
import Loader from "../../components/Loader/Loader";
import './show.scss';

const LinkCredenciales = (props) => {
    const status = [4,7];
    if(status.indexOf(props.campoClinico.estatus.id)>-1){
        return (<a href={`/formato/campo_clinico/${props.campoClinico.id}/credenciales/download`} target={'_blank'}>Credenciales</a>);
    }
    return (<></>);
}

const LinkFormatoFofoe = (props) => {
    const status = [2, 3, 4, 5, 6, 7];
    if (status.indexOf(props.campoClinico.estatus.id) > -1) {
        return (<a href={`/formato/campo_clinico/${props.campoClinico.id}/formato_fofoe/download`} target={'_blank'}>Formato
            FOFOE</a>);
    }
    return (<></>);
}

const ComprobanteOficio = (props) => {
    if(props.solicitud.fechaComprobante)
        return (<a href={`/came/solicitud/${props.solicitud.id}/oficio`} target={'_blank'}>Descargar</a>);
    return (<></>);
}

const LinkPago = (props) => {
    if(props.pago)
        return (<a href={`/pago/${props.pago.id}/download`} target={'_blank'}>Comprobante de pago</a>)
    return (<></>);
}

const LinkFactura = (props) => {
    if(props.factura)
        return (<a href={`/factura/${props.factura.id}/download`} target={'_blank'}>Factura</a>)
    return (<></>);
}

const searchPago = (pagos, campo_clinico) =>{
    const results =  pagos.filter(item => {
        return campo_clinico.referenciaBancaria.toString() === item.referenciaBancaria.toString();
    });
    if(results.length > 0){
        return results[0];
    }
    return null;
}

const DetalleSolicitudDetallado = (props) => {
    const [isLoading, setIsLoading] = React.useState(false)

    const [camposClinicos, setCamposClinicos] = React.useState(props.solicitud.campoClinicos);

    const [query, setQuery] = React.useState({});

    const handleSearchEvent = (query) => {
        setIsLoading(true);
        let querystring = '';
        for (const i in query) {
            querystring += `${i}=${query[i]}&`;
        }

        fetch(`/came/api/solicitud/${props.solicitud.id}/campos_clinicos?${querystring}`)
            .then(response => {
                return response.json()
            }, error => {
                console.error(error)
            })
            .then(json => {
                setCamposClinicos(json.data)
            })
            .finally(() => {
                setIsLoading(false);
            });

    }

    return (
        <>
            <div className="table-responsive">
                <table className="table">
                    <thead>
                    <tr>
                        <th>Sede <br/> <input type="text" placeholder={'Sede'}
                                   onChange={e => {setQuery(Object.assign(query,{unidad: e.target.value})); handleSearchEvent()}}/></th>
                        <th>Campo Clínico <br/> <input type="text" placeholder={'Campo Clínico'}
                                   onChange={e => {setQuery(Object.assign(query,{cicloAcademico: e.target.value})); handleSearchEvent()}}/></th>
                        <th>Nivel <br/> <input type="text" placeholder={'Nivel'}
                                   onChange={e => {setQuery(Object.assign(query,{nivelAcademico: e.target.value} )); handleSearchEvent()}}/></th>
                        <th>Carrera <br/> <input type="text" placeholder={'Carrera'}
                                   onChange={e => {setQuery(Object.assign(query, {carrera: e.target.value})); handleSearchEvent()}}/></th>
                        <th>No. de lugares</th>
                        <th>Fechas</th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody>
                    {camposClinicos.map(cc => {
                        return (
                            <tr key={cc.id}>
                                <td>{cc.unidad.nombre}</td>
                                <td>{cc.convenio.cicloAcademico.nombre}</td>
                                <td>{cc.convenio.carrera.nivelAcademico.nombre}</td>
                                <td>{cc.convenio.carrera.nombre}</td>
                                <td>Solicitados {cc.lugaresSolicitados} <br/> Autorizados {cc.lugaresAutorizados}</td>
                                <td>Inicio {cc.fechaInicialFormatted} <br/> Final {cc.fechaFinalFormatted}</td>
                                <td>
                                    <LinkPago pago={searchPago(props.solicitud.pagos, cc)}/> <br/>
                                    <LinkFactura factura={searchPago(props.solicitud.pagos, cc) ? searchPago(props.solicitud.pagos, cc).factura: null}/> <br/>
                                    <LinkFormatoFofoe campoClinico={cc}/> <br/>
                                    <LinkCredenciales campoClinico={cc}/>
                                </td>
                            </tr>
                        )
                    })}
                    </tbody>
                </table>
            </div>
        </>
    )
}

const ExpedienteUnico = (props) => {
    return (
        <div className="table-responsive">
            <table className="table">
                <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Archivo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Oficio de Montos de Colegiatura e Inscripción</td>
                    <td>{props.solicitud.fechaComprobanteFormatted}</td>
                    <td><ComprobanteOficio solicitud={props.solicitud}/></td>
                </tr>
                <tr>
                    <td>Comprobante de Pago</td>
                    <td>{props.solicitud.pago? props.solicitud.pago.fechaPagoFormatted: ''}</td>
                    <td><LinkPago pago={props.solicitud.pago}/></td>
                </tr>
                <tr>
                    <td>Factura (CFDI)</td>
                    <td>{props.solicitud.pago && props.solicitud.pago.factura? props.solicitud.pago.factura.fechaFacturacionFormatted: ''}</td>
                    <td><LinkFactura factura={props.solicitud.pago && props.solicitud.pago.factura? props.solicitud.pago.factura :null}/></td>
                </tr>
                </tbody>
            </table>
        </div>
    )
}

const ExpedienteDetallado = (props) => {
    return (
        <div className="table-responsive">
            <table className="table">
                <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Archivo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Oficio de Montos de Colegiatura e Inscripción</td>
                    <td>{props.solicitud.fechaComprobanteFormatted}</td>
                    <td><ComprobanteOficio solicitud={props.solicitud}/></td>
                </tr>
                </tbody>
            </table>
        </div>
    )
}

const DetalleSolicitudUnico = (props) => {

    const [isLoading, setIsLoading] = React.useState(false)

    const [camposClinicos, setCamposClinicos] = React.useState(props.solicitud.campoClinicos);

    const [query, setQuery] = React.useState({});

    const handleSearchEvent = () => {
        setIsLoading(true);
        let querystring = '';
        for (const i in query) {
            querystring += `${i}=${query[i]}&`;
        }

        fetch(`/came/api/solicitud/${props.solicitud.id}/campos_clinicos?${querystring}`)
            .then(response => {
                return response.json()
            }, error => {
                console.error(error)
            })
            .then(json => {
                setCamposClinicos(json.data)
            })
            .finally(() => {
                setIsLoading(false);
            });

    }

    return (
        <><Loader show={isLoading}/>
            <div className="table-responsive">
                <table className="table">
                    <thead>
                    <tr>
                        <th>Sede <br/> <input type="text" placeholder={'Sede'}
                                   onChange={e => {setQuery(Object.assign(query,{unidad: e.target.value})); handleSearchEvent()}}/></th>
                        <th>Campo Clínico <br/> <input type="text" placeholder={'Campo Clínico'}
                                   onChange={e => {setQuery(Object.assign(query,{cicloAcademico: e.target.value})); handleSearchEvent()}}/></th>
                        <th>Nivel <br/> <input type="text" placeholder={'Nivel'}
                                   onChange={e => {setQuery(Object.assign(query,{nivelAcademico: e.target.value} )); handleSearchEvent()}}/></th>
                        <th>Carrera <br/> <input type="text" placeholder={'Carrera'}
                                   onChange={e => {setQuery(Object.assign(query, {carrera: e.target.value})); handleSearchEvent()}}/></th>
                        <th>No. de lugares</th>
                        <th>Fechas</th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody>
                    {camposClinicos.map(cc => {
                        return (
                            <tr key={cc.id}>
                                <td>{cc.unidad.nombre}</td>
                                <td>{cc.convenio.cicloAcademico.nombre}</td>
                                <td>{cc.convenio.carrera.nivelAcademico.nombre}</td>
                                <td>{cc.convenio.carrera.nombre}</td>
                                <td>Solicitados {cc.lugaresSolicitados} <br/> Autorizados {cc.lugaresAutorizados}</td>
                                <td>Inicio {cc.fechaInicialFormatted} <br/> Final {cc.fechaFinalFormatted}</td>
                                <td>
                                    <LinkFormatoFofoe campoClinico={cc}/> <br/>
                                    <LinkCredenciales campoClinico={cc}/>
                                </td>
                            </tr>
                        )
                    })}
                    </tbody>
                </table>
            </div>
        </>
    )
}

const SolicitudShow = (props) => {
    const Detalle = () => {
        if (props.solicitud.tipoPago === 'Multiple')
            return (<div className={'panel panel-default came-table-detalle'}><DetalleSolicitudDetallado solicitud={props.solicitud}/></div>)
        return (<div className={'panel panel-default came-table-detalle'}><DetalleSolicitudUnico solicitud={props.solicitud}/></div>)
    }

    const Expediente = () => {
        if (props.solicitud.tipoPago === 'Multiple')
            return (<ExpedienteDetallado solicitud={props.solicitud}/>)
        return (<ExpedienteUnico solicitud={props.solicitud}/>)
    }

    return (
        <>
            <div className="col-md-12">
                <p><strong>No. de Solicitud:</strong> {props.solicitud.noSolicitud}</p>
            </div>
            <div className="col-md-12">
                <p><strong>Estado:</strong> {props.solicitud.estatusCameFormatted}</p>
            </div>
            <div className="col-md-12">
                <p><strong>Institución Educativa:</strong> {props.solicitud.institucion.nombre}</p>
            </div>
            <div className="col-md-12">
                <p>Se <strong>autorizaron</strong> {props.solicitud.camposClinicosAutorizados} de {props.solicitud.camposClinicosSolicitados} Campos
                    Clinicos solicitados.</p>
            </div>
            <div className="col-md-8"/>
            <div className="col-md-12">
                <Detalle/>
            </div>
            <div className="col-md-6">
                <h2>Expediente</h2>
            </div>
            <div className="col-md-6">
                <h2>Convenios</h2>
            </div>
            <div className="col-md-6">
                <Expediente/>
            </div>
            <div className="col-md-6">
                <div className="table-responsive">
                    <table className="table">
                        <thead>
                        <tr>
                            <th>Número</th>
                            <th>Grado</th>
                            <th>Ciclo</th>
                            <th>Carrera</th>
                            <th>Vigencia</th>
                        </tr>
                        </thead>
                        <tbody>
                        {props.convenios.map((convenio, i) => {
                            return (
                                <tr key={i}>
                                    <td>{convenio.numero}</td>
                                    <td>{convenio.carrera.nivelAcademico.nombre}</td>
                                    <td>{convenio.cicloAcademico.nombre}</td>
                                    <td>{convenio.carrera.nombre}</td>
                                    <td>{convenio.vigenciaFormatted}</td>
                                </tr>
                            )
                        })}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    )
}

export default SolicitudShow;

import * as React from 'react'
import Loader from "../../components/Loader/Loader";

const ComprobanteOficio = (props) => {
    if(props.solicitud.fechaComprobante)
        return (<a href={`/solicitud/${props.solicitud.id}/oficio`} target={'_blank'}>Descargar</a>);
    return (<></>);
}

const LinkPago = (props) => {
    if(props.pago)
        return (<a href={`/pago/${props.pago.id}/download`} target={'_blank'}>Descargar</a>)
    return (<></>);
}

const LinkFactura = (props) => {
    if(props.factura)
        return (<a href={`/factura/${props.factura.id}/download`} target={'_blank'}>Descargar</a>)
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

    const handleSearchEvent = (query) => {
        setIsLoading(true);
        let querystring = '';
        for (const i in query) {
            querystring += `${i}=${query[i]}&`;
        }

        fetch(`/api/came/solicitud/${props.solicitud.id}/campos_clinicos?${querystring}`)
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
                        <th><input type="text" placeholder={'Sede'}
                                   onChange={e => handleSearchEvent({unidad: e.target.value})}/></th>
                        <th><input type="text" placeholder={'Campo Clínico'}
                                   onChange={e => handleSearchEvent({cicloAcademico: e.target.value})}/></th>
                        <th><input type="text" placeholder={'Nivel'}
                                   onChange={e => handleSearchEvent({nivelAcademico: e.target.value})}/></th>
                        <th><input type="text" placeholder={'Carrera'}
                                   onChange={e => handleSearchEvent({carrera: e.target.value})}/></th>
                        <th>No. de lugares solicitados</th>
                        <th>No. de lugares autorizados</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Término</th>
                        <th>Comprobante</th>
                        <th>Factura</th>
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
                                <td>{cc.lugaresSolicitados}</td>
                                <td>{cc.lugaresAutorizados}</td>
                                <td>{cc.fechaInicialFormatted}</td>
                                <td>{cc.fechaFinalFormatted}</td>
                                <td><LinkPago pago={searchPago(props.solicitud.pagos, cc)}/></td>
                                <td><LinkFactura factura={searchPago(props.solicitud.pagos, cc) ? searchPago(props.solicitud.pagos, cc).factura: null}/></td>
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
    const Comprobante = () => {
        if(props.solicitud.fechaComprobante)
            return (<a href={`/solicitud/${props.solicitud.id}/oficio`} target={'_blank'}>Descargar</a>);
        return (<></>);
    }
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

    const handleSearchEvent = (query) => {
        setIsLoading(true);
        let querystring = '';
        for (const i in query) {
            querystring += `${i}=${query[i]}&`;
        }

        fetch(`/api/came/solicitud/${props.solicitud.id}/campos_clinicos?${querystring}`)
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
                        <th><input type="text" placeholder={'Sede'}
                                   onChange={e => handleSearchEvent({unidad: e.target.value})}/></th>
                        <th><input type="text" placeholder={'Campo Clínico'}
                                   onChange={e => handleSearchEvent({cicloAcademico: e.target.value})}/></th>
                        <th><input type="text" placeholder={'Nivel'}
                                   onChange={e => handleSearchEvent({nivelAcademico: e.target.value})}/></th>
                        <th><input type="text" placeholder={'Carrera'}
                                   onChange={e => handleSearchEvent({carrera: e.target.value})}/></th>
                        <th>No. de lugares solicitados</th>
                        <th>No. de lugares autorizados</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Término</th>
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
                                <td>{cc.lugaresSolicitados}</td>
                                <td>{cc.lugaresAutorizados}</td>
                                <td>{cc.fechaInicialFormatted}</td>
                                <td>{cc.fechaFinalFormatted}</td>
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
            return (<DetalleSolicitudDetallado solicitud={props.solicitud}/>)
        return (<DetalleSolicitudUnico solicitud={props.solicitud}/>)
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
                <p><strong>Insitución Educativa:</strong> {props.solicitud.institucion.nombre}</p>
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

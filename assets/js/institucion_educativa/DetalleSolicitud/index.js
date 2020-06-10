import React from 'react';
import ReactDOM from 'react-dom'
import { solicitudesGet } from "../api/camposClinicos";
import { getActionNameByInstitucionEducativa, isActionDisabledByInstitucionEducativa } from "../../utils";
import { SOLICITUD } from "../../constants";

const ListaCampos = ({
    institucion,
    solicitud,
    total,
    autorizado,
    pago,
    campos }) => {

        const { useState, useEffect } = React
        const [ camposClinicos, setCamposClinicos ] = useState([])
        const [ search, setSearch ] = useState('')
        const [ isLoading, toggleLoading ] = useState(false)


        function handleStatusAction(solicitud) {
            { console.log(solicitud) }
            if(isActionDisabledByInstitucionEducativa(solicitud.estatus)) return;

            let redirectRoute = ''
            if(
                solicitud.estatus === SOLICITUD.CARGANDO_COMPROBANTES
            ) {
                redirectRoute = `/instituciones/${institucion}/solicitudes/${solicitud.id}/campos-clinicos`
                { console.log("opcion 1") }
            } else {
                { console.log("opcion 2") }
                switch(solicitud.estatus) {
            case SOLICITUD.CONFIRMADA:
                redirectRoute = `/instituciones/${institucion}/solicitudes/${solicitud.id}/registrar`
                }
            }

            window.location.href = redirectRoute
        }

        let isPago = false;
        let isFactura = false;

        if(pago[0]){
            isPago = true;
            if(pago[0].factura)
                isFactura = true;
        }
        else
            isPago = false;


        {console.log(solicitud)}

        useEffect(() => {
            if(
                search === null ||
                search == ''
            ) {
                getCamposClinicos()
            }
        }, [search])

        function handleSearch() {
            if(!search) return;
            getCamposClinicos()
        }

        function getCamposClinicos() {
            toggleLoading(true)

            solicitudesGet(
                institucion,
                solicitud,
                search
            )
            .then((res) => {
                {console.log(res)}
                setCamposClinicos(res.camposClinicos)
            })
            .finally(() => {
                toggleLoading(false)
            })
        }


        {console.log(campos)}

    return(
        <div className='row'>
            <div className="col-md-12 mt-10">
                <p>Se autorizaron {autorizado} de {total} campos clínicos</p>
            </div>
            <div className="col-md-6 mt-10">
                <p className='text-bold'>Estado: {campos[0].solicitud.estatus} </p>
            </div>

            <div className="col-md-6 mt-10">
                Acción
                <button
                    className='btn btn-default'
                    disabled={isActionDisabledByInstitucionEducativa(campos[0].solicitud.estatus)}
                    onClick={() => handleStatusAction(campos[0].solicitud)}
                >
                    {getActionNameByInstitucionEducativa(campos[0].solicitud.estatus, false)}
                </button>
            </div>


            <div className="col-md-12 mt-10">
                <div className="panel panel-default">
                    <div className="panel-body">
                        <table className='table'>
                            <thead className='headers'>
                                <tr>
                                    <th>Sede</th>
                                    <th>Campo clínico</th>
                                    <th>Nivel</th>
                                    <th>Carrera</th>
                                    <th>No. lugares solicitados</th>
                                    <th>No. lugares autorizados</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Termino</th>
                                    <th>No. de semanas</th>
                                </tr>
                            </thead>
                            <tbody>
                            {
                                isLoading ?
                                <tr>
                                    <th className='text-center' colSpan={9}>Cargando información...</th>
                                </tr> :
                                camposClinicos.map((item, index) => {
                                return <tr key={index}>
                                    <td>{item.unidad.nombre ? item.unidad.nombre : 'No asignado'}</td>
                                    <td>{item.convenio.cicloAcademico ? item.convenio.cicloAcademico.nombre : 'No asignado'}</td>
                                    <td>{item.convenio.carrera ? item.convenio.carrera.nivelAcademico.nombre : 'No asignado' }</td>
                                    <td>{item.convenio.carrera ? item.convenio.carrera.nombre : 'No asignado'}</td>
                                    <td>{item.lugaresSolicitados}</td>
                                    <td>{item.lugaresAutorizados}</td>
                                    <td>{item.fechaInicial}</td>
                                    <td>{item.fechaFinal}</td>
                                    <td>{item.weeks}</td>
                                </tr>
                                })
                            }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div className="col-md-12">
                <p className="text-bold mt-10">Expediente</p>
                <div className="panel panel-default">
                    <div className="panel-body">
                        <table className='table'>
                            <thead className='headers'>
                                <tr>
                                    <th>Documento</th>
                                    <th>Fecha</th>
                                    <th>Descripcion</th>
                                    <th>Archivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{campos[0].solicitud.documento ? campos[0].solicitud.documento : 'Oficio de Montos de Colgiatura e inscripción'}</td>
                                    <td>{campos[0].solicitud.fechaComprobante ? campos[0].solicitud.fechaComprobante : ''}</td>
                                    <td>{campos[0].solicitud.descripcion ? campos[0].solicitud.descripcion : ''}</td>
                                    <td><a href='#'>{campos[0].solicitud.urlArchivo ? campos[0].solicitud.urlArchivo : ''}</a></td>
                                </tr>
                                {
                                    isPago ?

                                    <tr>
                                        <td>Comprobante de pago</td>
                                        <td>{pago[0].fechaPago}</td>
                                        <td>Pago ref: {pago[0].referenciaBancaria}</td>
                                        <td><a href='#'>{pago[0].comprobantePago}</a></td>
                                    </tr> :
                                    <tr>
                                        <td>Comprobante de pago</td>
                                        <td></td>
                                        <td>No se ha cargado información</td>
                                        <td></td>
                                    </tr>
                                }
                                {
                                    isFactura ?

                                    <tr>
                                        <td>Factura (CFDI)</td>
                                        <td>{pago[0].factura.fechaFacturacion}</td>
                                        <td></td>
                                        <td><a href='#'>{pago[0].factura.zip}</a></td>
                                    </tr> :
                                    <tr>
                                        <td>Factura (CFDI)</td>
                                        <td></td>
                                        <td>No se solicitó factura</td>
                                        <td></td>
                                    </tr>
                                }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    )
}


ReactDOM.render(
    <ListaCampos
    institucion = {window.INSTITUCION_PROP}
    solicitud={window.SOLICITUD_PROP}
    total={ window.TOTAL_PROP}
    autorizado={window.AUTORIZADO_PROP}
    campos={window.CAMPOS_PROP}
    pago={window.PAGO_PROP} />
,document.getElementById('detalle-solicitud'));

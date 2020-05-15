import React from 'react';
import ReactDOM from 'react-dom'
import { solicitudesGet } from "../api/camposClinicos";

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

        let isPago;
        let isFactura;

        if(pago)
            isPago = true;
        else
            isPago = false;

        if(pago[0].factura)
            isFactura = true;
        else
            isFactura = false;



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

        {console.log(pago)}

    return(
        <div className='row'>
            <div className="col-md-12 mt-10">
                <p>Se autorizaron {autorizado} de {total} campos clínicos</p>
            </div>
            <div className="col-md-6 mt-10">
                <p className='text-bold'>Estado: {campos[0].solicitud.estatus} </p>
            </div>

            <div className="col-md-6 mt-10">
                <p className='text-bold'>Acción: {campos[0].solicitud.estatus}</p>
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
                                    <td>{item.unidad.nombre}</td>
                                    <td>{item.convenio.cicloAcademico.nombre}</td>
                                    <td>{item.convenio.carrera.nivelAcademico.nombre}</td>
                                    <td>{item.convenio.carrera.nombre}</td>
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
                                    <td>{campos[0].solicitud.documento}</td>
                                    <td>{campos[0].solicitud.fechaComprobante}</td>
                                    <td>{campos[0].solicitud.descripcion}</td>
                                    <td><a href='#'>{campos[0].solicitud.urlArchivo}</a></td>
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
                                        <td>{pago[0].facturas.fechaFacturacion}</td>
                                        <td></td>
                                        <td><a href='#'>{pago[0].facturas.zip}</a></td>
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
,document.getElementById('solicitud-index-component'));

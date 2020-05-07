import React from 'react';
import ReactDOM from 'react-dom'
import { solicitudesGet } from "../api/camposClinicos";
//import { ESTATUS_TEXTS } from "../Solicitud/Index/constants";

const ListaCampos = ({
    institucion,
    solicitud, 
    total,
    autorizado,
    campos }) => {

        const { useState, useEffect } = React
        const [ camposClinicos, setCamposClinicos ] = useState([])
        const [ search, setSearch ] = useState('')
        const [ isLoading, toggleLoading ] = useState(false)

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


    return(
        <div className='row'>
            <div className="col-md-12 mt-10">
                <p>Se autorizaron {autorizado} de {total} campos clínicos</p>
            </div>
            <div className="col-md-6 mt-10">
                <p className='text-bold'>Estado: {ESTATUS_TEXTS[campos[0].solicitud.estatus].title} </p>
            </div>

            <div className="col-md-6 mt-10">
                <p className='text-bold'>Acción: {ESTATUS_TEXTS[campos[0].solicitud.estatus].button}</p>
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
                                    <td>{item.cicloAcademico.nombre}</td>
                                    <td>{item.carrera.nivelAcademico.nombre}</td>
                                    <td>{item.carrera.nombre}</td>
                                    <td>{item.lugaresSolicitados}</td>
                                    <td>{item.lugaresAutorizados}</td>
                                    <td>{item.fechaInicial.toLocaleDateString}</td>
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
        </div>
        
    )
}

const ListaExpediente = ({ expediente }) => {
    return(
        <div className='row'>
            <div className="col-md-12">
                <p className="text-bold mt-10">Convenios vigentes de la institución educativa</p>
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
                            {
                                expediente.map((item, index) => {
                                return <tr key={index}>
                                    <td>Documento</td>
                                    <td>{item.fecha}</td>
                                    <td>{item.descripcion}</td>
                                    <td><a>{item.urlArchivo}</a></td>
                                </tr>
                            })}
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
    campos={window.CAMPOS_PROP} />
,document.getElementById('solicitud-index-component'));

ReactDOM.render( 
    <ListaExpediente
    expediente={window.EXPEDIENTE_PROP} />
,document.getElementById('expediente-component'));


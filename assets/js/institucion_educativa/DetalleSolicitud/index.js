import React, { Component } from 'react';
import ReactDOM from 'react-dom'

const ListaCampos = ({ solicitud }) => {
    console.log(solicitud)
    return(
        <table className='table table-bordered'>
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
                solicitud.map((item) => {
                return <tr>
                    <td>Sede</td>
                    <td>{item.cicloAcademico.nombre}</td>
                    <td>{item.carrera.nivelAcademico.nombre}</td>
                    <td>{item.carrera.nombre}</td>
                    <td>{item.lugaresSolicitados}</td>
                    <td>{item.lugaresAutorizados}</td>
                    <td>{item.fechaInicial}</td>
                    <td>{item.fechaFinal}</td>
                    <td>2</td>
                </tr>
            })}
            </tbody>
        </table>
    )
}

const ListaExpediente = ({ expediente }) => {
    console.log(expediente)
    return(
        <table className='table table-bordered'>
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
                expediente.map((item) => {
                return <tr>
                    <td>Documento</td>
                    <td>{item.fecha}</td>
                    <td>{item.descripcion}</td>
                    <td><a>{item.urlArchivo}</a></td>
                </tr>
            })}
            </tbody>
        </table>
    )
}

ReactDOM.render( <ListaCampos solicitud={window.SOLICITUD_PROP}/>,document.getElementById('listaSolicitud'));
ReactDOM.render( <ListaExpediente expediente={window.EXPEDIENTE_PROP}/>,document.getElementById('listaExpediente'));
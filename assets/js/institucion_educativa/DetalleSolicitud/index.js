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

ReactDOM.render( <ListaCampos solicitud={window.SOLICITUD_PROP}/>,document.getElementById('listaSolicitud'));
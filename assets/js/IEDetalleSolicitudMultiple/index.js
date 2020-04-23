import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import solicitud from './solicitud.json'
import expediente from './expediente.json'

class App extends Component{
	render(){
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
                        <th>Comprobante</th> 
                        <th>Factura</th> 
                    </tr>
                </thead>
                <tbody>
                {
                    solicitud.map((item) => {
                    return <tr>
                        <td>{item.sede}</td>
                        <td>{item.ciclo}</td>
                        <td>{item.nivel}</td>
                        <td>{item.campo}</td>
                        <td>{item.camposSolicitados}</td>
                        <td>{item.camposAprobados}</td>
                        <td>{item.fechaInicio}</td>
                        <td>{item.fechaTermino}</td>
                        <td>{item.comprobante}</td>
                        <td>{item.factura}</td>
                    </tr>
                })}
                </tbody>
            </table>
        )
	}
}

class Expediente extends Component{
	render(){
		return(
        	<table className='table table-bordered'>
                <thead className='headers'>
                    <tr>
                        <th>Documento</th>
                        <th>Fecha</th>
                        <th>Descripción</th> 
                        <th>Archivo</th> 
                    </tr>
                </thead>
                <tbody>
                {
                    expediente.map((item) => {
                    return <tr>
                        <td>{item.documento}</td>
                        <td>{item.fecha}</td>
                        <td>{item.descripcion}</td>
                        <td><a href={item.url_archivo}>{item.archivo}</a></td>
                    </tr>
                })}
                </tbody>
            </table>
        )
	}
}

const Name = ({ Institution }) => {
  return(
    <p style={{ marginTop: '10px', fontSize: '16px', fontWeight: 'bold'}}>{Institution}</p>
  )
}

const Request = ({ noSolicitud }) => {
  return(
    <p style={{ marginTop: '10px', fontSize: '16px', fontWeight: 'bold'}}>{noSolicitud}</p>
  )
}


ReactDOM.render( <Name Institution={window.name} />, document.getElementById('ie'));
ReactDOM.render( <Request noSolicitud={window.noSolicitud} />, document.getElementById('noSolicitud'));
ReactDOM.render( <App/>,document.getElementById('listaSolicitud'));
ReactDOM.render( <Expediente/>,document.getElementById('listaExpediente'));
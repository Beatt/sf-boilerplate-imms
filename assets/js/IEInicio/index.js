import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import campos from './solicitud.json'

class App extends Component{
    render(){
        return(
            <table className='table-bordered'>
                <thead className='headers'>
                    <tr>
                        <th>No. de solicitud</th>
                        <th>No. de campos clínicos solicitados</th> 
                        <th>No. de campos clínicos autorizados</th> 
                        <th>Fecha solicitud</th> 
                        <th>Estado</th> 
                        <th>Acción</th> 
                    </tr>
                </thead>
                <tbody>
                {
                    campos.map((item) => {
                    return <tr>
                        <td>{item.id}</td>
                        <td>{item.camposSolicitados}</td>
                        <td>{item.camposAprobados}</td>
                        <td>{item.fechaSolicitud}</td>
                        <td>{item.estado}</td>
                        <td><a>Registo de montos</a></td>

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

ReactDOM.render( <App/>,document.getElementById('listaCampos'));

ReactDOM.render( <Name Institution={window.name} />, document.getElementById('ie'));


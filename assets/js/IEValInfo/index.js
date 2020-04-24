import React, { Component } from 'react';
import ReactDOM from 'react-dom'
import './styles.scss'
import convenio from './convenio.json'

class App extends Component{
    render(){
        return(
            <table className='table table-bordered'>
                <thead className='headers'>
                    <tr>
                        <th>Número</th>
                        <th>Grado</th>
                        <th>Ciclo</th> 
                        <th>Carrera</th>
                        <th>Vigencia</th>
                    </tr>
                </thead>
                <tbody>
                {
                    convenio.map((item) => {
                    return <tr>
                        <td>{item.numero}</td>
                        <td>{item.grado}</td>
                        <td>{item.ciclo}</td>
                        <td>{item.carrera}</td>
                        <td>{item.vigencia}</td>
                    </tr>
                })}
                </tbody>
            </table>
        )
    }
}

ReactDOM.render( <App/>,document.getElementById('listaConvenio'));


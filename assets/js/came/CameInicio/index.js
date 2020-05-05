import React from 'react'
import ReactDOM from 'react-dom'
import { solicitud } from '../ApiCame/CameInicio'


const style = {
  border: '1px solid black',
}

class CameInicio extends React.Component {
  render(){
    return(
        
      <div>
        <hr/>
        <div>Solicitudes de Campos Clínicos</div>
        <hr/>
        <div>
          <input type="submit" value="Agregar Solicitud" />
        </div>
        <div>

          <input type="text" placeholder="Buscar por" />
          <input type="submit" value="Buscar"/>
        </div>
        <div>
          <TablaSolicitud />
        </div>
      </div>
    )
  }
}

const TablaSolicitud = () => {
  return (
    <table style={style}>
      <thead>
        <td>
          <tr>
            No. de solicitud
          </tr>
        </td>
        <td>
          <tr>
            Institución Educativa
          </tr>
        </td>
        <td>
          <tr>
            No. de campos clínicos solicitados
          </tr>
        </td>
        <td>
          <tr>
            No. de campos clínicos autorizados
          </tr>
        </td>
        <td>
          <tr>
            Fecha Solicitud
          </tr>
        </td>
        <td>
          <tr>
            Estado
          </tr>
        </td>
        <td>
          <tr>
            Acciones
          </tr>
        </td>
      </thead>
      
      <tbody>
        <td>
          <tr>
            respuesta fila uno
          </tr>
        </td>
        <td>
          <tr>
            respuesta fila dos
          </tr>
        </td>
      </tbody>


    </table>
  )
}


ReactDOM.render(console.log(solicitud), document.getElementById('solicitudes-table'))
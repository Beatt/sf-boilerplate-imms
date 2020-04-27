import * as React from 'react'
import ReactDOM from 'react-dom'

const Index = () => {
  return(
    <table className='table table-bordered'>
      <thead className='headers'>
        <tr>
          <th>No. de solicitud</th>
          <th>No. de campos clínicos <br/>solicitados</th>
          <th>No. de campos clínicos <br/>autorizados</th>
          <th>Fecha de solicitud</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  )
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Index/>,
    document.getElementById('solicitud-index-component')
  )
})

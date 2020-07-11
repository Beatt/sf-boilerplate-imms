import * as React from 'react'
const DEFAULT_DOCUMENT_VALUE = '-'

const Expediente = ({ expediente }) => (
  <div className='panel panel-default'>
    <div className='panel-body'>
      <table className='table'>
        <thead>
        <tr>
          <th>Documento</th>
          <th>Descripción</th>
          <th>Fecha</th>
          <th>Archivo</th>
        </tr>
        </thead>
        <tbody>
          <tr>
            <td>{expediente.oficioMontos.nombre}</td>
            <td>{expediente.oficioMontos.descripcion || DEFAULT_DOCUMENT_VALUE}</td>
            <td>{expediente.oficioMontos.fecha || DEFAULT_DOCUMENT_VALUE}</td>
            <td>
              {
                expediente.oficioMontos.urlArchivo ?
                  <a href={expediente.oficioMontos.urlArchivo}>Descargar</a> :
                  DEFAULT_DOCUMENT_VALUE
              }
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
)

export default Expediente

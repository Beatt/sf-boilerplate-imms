import * as React from 'react'

const Expediente = ({ expediente }) => (
  <div className='panel panel-default'>
    <div className='panel-body'>
      <table className='table'>
        <thead>
        <tr>
          <th>Documento</th>
          <th>Fecha</th>
          <th>Descripción</th>
          <th>Archivo</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>{expediente.documento}</td>
          <td>{expediente.fechaComprobante}</td>
          <td>{expediente.expedienteDescripcion}</td>
          <td>
            <a
              href={expediente.urlArchivo}
              target='_blank'
            >
              Descargar oficio de montos
            </a>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
)

export default Expediente

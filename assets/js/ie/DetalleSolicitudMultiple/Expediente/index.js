import * as React from 'react'
import {dateFormat} from "../../../utils";
const DEFAULT_DOCUMENT_VALUE = '-'

const Expediente = ({ expediente }) => {

  function getUniqCamposClinicos(comprobantesPago) {
    return [...new Set(comprobantesPago.map(item => item.descripcion))];
  }

  return(
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
            <td>{dateFormat(expediente.oficioMontos.fecha) || DEFAULT_DOCUMENT_VALUE}</td>
            <td>
              {
                expediente.oficioMontos.urlArchivo ?
                  <a
                    href={expediente.oficioMontos.urlArchivo}
                    target='_blank'
                  >
                    Descargar
                  </a> :
                  DEFAULT_DOCUMENT_VALUE
              }
            </td>
          </tr>
          {
            getUniqCamposClinicos(expediente.comprobantesPago).map((item, index) => (
              <tr key={index}>
                <td>Comprobante de pago de {item}</td>
                <td></td>
                <td>
                  {
                    expediente.comprobantesPago.map((comprobante, key) => {
                      if(comprobante.descripcion === item) {
                        return(
                          <span
                            key={key}
                          >
                            {dateFormat(comprobante.fecha)}
                            <br/>
                          </span>
                        );
                      }
                    })
                  }
                </td>
                <td>
                  {
                    expediente.comprobantesPago.map((comprobante, key) => {
                      if(comprobante.descripcion === item) {
                        return(
                          <a
                            key={key}
                            href={comprobante.urlArchivo}
                            target='_blank'
                          >
                            Descargar <br/>
                          </a>
                        );
                      }
                    })
                  }
                </td>
              </tr>
            ))
          }
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default Expediente

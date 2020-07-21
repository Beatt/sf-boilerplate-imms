import * as React from 'react'
const DEFAULT_DOCUMENT_VALUE = '-'

const Expediente = ({ expediente }) => {

  function getUniqCamposClinicos(comprobantesPago) {
    return [...new Set(comprobantesPago.map(item => item.options.unidad))];
  }

  return(
    <div className='panel panel-default'>
      <div className='panel-body'>
        <table className='table'>
          <thead>
          <tr>
            <th className='col-md-3'>Documento</th>
            <th className='col-md-7'>Descripción</th>
            <th className='col-md-1'>Fecha</th>
            <th className='col-md-1'>Archivo</th>
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
                <td>Comprobante de pago del campo clínico {item}</td>
                <td>
                  {
                    expediente.comprobantesPago.map((comprobante, key) => {
                      if(comprobante.options.unidad === item) {
                        return(
                          <span
                            key={key}
                          >
                            {comprobante.descripcion}
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
                      if(comprobante.options.unidad === item) {
                        return(
                          <span
                            key={key}
                          >
                            {comprobante.fecha}
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
                      if(comprobante.options.unidad === item) {
                        return(
                          <a
                            key={key}
                            href={`/ie/pagos/${comprobante.options.pagoId}/descargar-comprobante-de-pago`}
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

import * as React from 'react'
import {getSchemeAndHttpHost} from "../../../utils";
const DEFAULT_DOCUMENT_VALUE = '-'

const Expediente = ({ solicitud }) => {

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
            <td>{solicitud.expediente.oficioMontos.nombre}</td>
            <td>{solicitud.expediente.oficioMontos.descripcion || DEFAULT_DOCUMENT_VALUE}</td>
            <td>{solicitud.expediente.oficioMontos.fecha || DEFAULT_DOCUMENT_VALUE}</td>
            <td>
              {
                solicitud.expediente.oficioMontos.urlArchivo ?
                  <a
                    href={`${getSchemeAndHttpHost()}${solicitud.expediente.oficioMontos.urlArchivo}`}
                    target='_blank' download
                  >
                    Descargar
                  </a> :
                  DEFAULT_DOCUMENT_VALUE
              }
            </td>
          </tr>
          {
            solicitud.expediente.formatosFofoe &&
            <tr>
              <td>{solicitud.expediente.formatosFofoe.nombre}</td>
              <td>{solicitud.expediente.formatosFofoe.descripcion}</td>
              <td>{solicitud.expediente.formatosFofoe.fecha}</td>
              <td>
                <a
                  href={`${getSchemeAndHttpHost()}/ie/solicitudes/${solicitud.id}/descargar-formatos-fofoe`}
                  target='_blank'
                >
                  Descargar
                </a>
              </td>
            </tr>
          }
          {
            getUniqCamposClinicos(solicitud.expediente.comprobantesPago).map((item, index) => (
              <tr key={index}>
                <td>Comprobante de pago del campo clínico {item}</td>
                <td>
                  {
                    solicitud.expediente.comprobantesPago.map((comprobante, key) => {
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
                    solicitud.expediente.comprobantesPago.map((comprobante, key) => {
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
                    solicitud.expediente.comprobantesPago.map((comprobante, key) => {
                      if(comprobante.options.unidad === item) {
                        return(
                          <a
                            key={key}
                            href={`${getSchemeAndHttpHost()}/ie/pagos/${comprobante.options.pagoId}/descargar-comprobante-de-pago`}
                            target='_blank' download
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

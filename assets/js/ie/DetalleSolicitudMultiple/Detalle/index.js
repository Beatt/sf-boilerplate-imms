import * as React from 'react'
import GestionPagoModal from "../../components/GestionPagoModal";
import {dateFormat} from "../../../utils";

const DetalleSolicitudMultiple = ({ solicitud }) => {
  const { useState } = React
  const [modalIsOpen, setModalIsOpen] = React.useState(false);
  const [campoClinicoSelected, setCampoClinicoSelected] = useState({
    pago: { id: null }
  })

  function getFactura(urlArchivo, requiereFactura) {
    if(requiereFactura === false) return 'No solicitada';
    if(!urlArchivo) return 'Pendiente';

    return(
      <a
        href={`${urlArchivo}`}
        target='_blank'
      >Descargar</a>
    )
  }

  function closeModal() {
    setModalIsOpen(false)
  }

  function isCampoClinicoAutorizado(lugaresAutorizados) {
    return lugaresAutorizados > 0;
  }

  return(
    <div className="panel panel-default">
      <div className="panel-body">
        <table className='table'>
          <thead className='headers'>
          <tr>
            <th>Sede</th>
            <th>Campo clínico</th>
            <th>Carrera</th>
            <th>No. de lugares <br/>solicitados</th>
            <th>No. de lugares <br/>autorizados</th>
            <th>Periodo</th>
            <th>Estado</th>
            <th>Comprobante</th>
            <th>Factura</th>
          </tr>
          </thead>
          <tbody>
          {
            solicitud.camposClinicos.map((campoClinico, index) =>
              <tr key={index}>
                <td>{campoClinico.unidad.nombre}</td>
                <td>{campoClinico.convenio.cicloAcademico.nombre}</td>
                <td>{campoClinico.convenio.carrera.nivelAcademico.nombre}. {campoClinico.convenio.carrera.nombre}</td>
                <td>{campoClinico.lugaresSolicitados}</td>
                <td>{campoClinico.lugaresAutorizados}</td>
                <td>{dateFormat(campoClinico.fechaInicial)} - {dateFormat(campoClinico.fechaFinal)}</td>
                <td>
                  {
                    isCampoClinicoAutorizado(campoClinico.lugaresAutorizados) &&
                    campoClinico.estatus
                  }
                </td>
                <td>
                  {
                    isCampoClinicoAutorizado(campoClinico.lugaresAutorizados) &&
                    campoClinico.estatus === 'Pago' ?
                      <button className='btn btn-default' disabled={true}>En validación por FOFOE</button> :
                      <button className="btn btn-success" onClick={() => {
                        setCampoClinicoSelected(campoClinico)
                        setModalIsOpen(true)
                      }}>Cargar comprobante</button>
                  }
                </td>
                <td>
                  {
                    isCampoClinicoAutorizado(campoClinico.lugaresAutorizados) &&
                    getFactura(campoClinico.pago.urlArchivo, campoClinico.requiereFactura)
                  }
                </td>
              </tr>
            )
          }
          </tbody>
        </table>
        {
          modalIsOpen &&
          <GestionPagoModal
            modalIsOpen={modalIsOpen}
            closeModal={closeModal}
            pagoId={campoClinicoSelected.pago.id}
          />
        }
      </div>
    </div>
  )
}

export default DetalleSolicitudMultiple

import * as React from 'react'
import GestionPagoModal from "../../components/GestionPagoModal";

const DetalleSolicitudMultiple = ({ initCamposClinicos }) => {
  const { useState } = React
  const [camposClinicos] = useState(initCamposClinicos)
  const [modalIsOpen, setModalIsOpen] = React.useState(false);
  const [campoClinicoSelected, setCampoClinicoSelected] = useState({
    pago: { id: null }
  })

  function getFactura(factura) {
    if(factura === 'Pendiente' || factura === 'No solicitada') return factura;

    return(
      <a href={`${factura}`}>Descargar factura</a>
    )
  }

  function closeModal() {
    setModalIsOpen(false)
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
            camposClinicos.map((campoClinico, index) =>
              <tr key={index}>
                <td>{campoClinico.unidad.nombre}</td>
                <td>{campoClinico.convenio.cicloAcademico.nombre}</td>
                <td>{campoClinico.convenio.carrera.nivelAcademico.nombre}. {campoClinico.convenio.carrera.nombre}</td>
                <td>{campoClinico.lugaresSolicitados}</td>
                <td>{campoClinico.lugaresAutorizados}</td>
                <td>{new Date(campoClinico.fechaInicial).toLocaleDateString()} - {new Date(campoClinico.fechaFinal).toLocaleDateString()}</td>
                <td>{campoClinico.estatus.nombre}</td>
                <td>
                  {
                    campoClinico.estatus.nombre === 'Pago' ?
                      <button className='btn btn-default' disabled={true}>En validación por FOFOE</button> :
                      <button className="btn btn-success" onClick={() => {
                        setCampoClinicoSelected(campoClinico)
                        setModalIsOpen(true)
                      }}>Cargar comprobante</button>
                  }
                </td>
                <td>{getFactura(campoClinico.factura)}</td>
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

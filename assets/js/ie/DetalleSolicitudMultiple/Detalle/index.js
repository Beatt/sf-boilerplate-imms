import * as React from 'react'
import GestionPagoModal from "../../components/GestionPagoModal";
import {CAMPO_CLINICO, SOLICITUD} from "../../../constants";
import { getActionNameByInstitucionEducativa } from "../../../utils";

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

  function getActionButton(campoClinico) {
    if(!isCampoClinicoAutorizado(campoClinico.lugaresAutorizados)) return;

    if(campoClinico.estatus === CAMPO_CLINICO.PAGO) {
      return(
        <button className='btn btn-default' disabled={true}>En validación por FOFOE</button>
      );
    }
    else if(campoClinico.estatus === CAMPO_CLINICO.PAGO_NO_VALIDO) {
      return(
        <button
          className='btn btn-default'
          onClick={() => {
            setCampoClinicoSelected(campoClinico)
            setModalIsOpen(true)
          }}
        >
          Corregir comprobante
        </button>
      );
    }

    return solicitud.estatus === SOLICITUD.CARGANDO_COMPROBANTES ?
      <button
        className="btn btn-success"
        onClick={() => {
          setCampoClinicoSelected(campoClinico)
          setModalIsOpen(true)
        }}
      >
        Cargar comprobante
      </button> : 'Pendiente';
  }

  return(
    <div className='row'>
      <div className="col-md-12">
        <p><span className="text-bold">No. solicitud:</span> {solicitud.noSolicitud}</p>
        <div className="row">
          <div className="col-md-6 mt-10">
            <p className="mb-20"><span className="text-bold">Estado de la solicitud:</span> {solicitud.estatus}</p>
          </div>
          {
            solicitud.estatus === SOLICITUD.FORMATOS_DE_PAGO_GENERADOS &&
            <div className="col-md-6">
              <strong>Acción</strong>&nbsp;
              <a
                href={`/ie/solicitudes/${solicitud.id}/detalle-de-forma-de-pago`}
                className='btn btn-default'
              >
                {getActionNameByInstitucionEducativa(solicitud.estatus, false)}
              </a>
            </div>
          }
        </div>
      </div>
      <div className="col-md-12 mt-20">
        <p><span className="text-bold">Se autorizarón:</span> {solicitud.totalCamposClinicosAutorizados} de {solicitud.camposClinicos.length} campos clínicos solicitados</p>
      </div>
      <div className="col-md-12 mt-10">
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
                <th className='col-md-2'>Periodo</th>
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
                    <td>{campoClinico.fechaInicial} - {campoClinico.fechaFinal}</td>
                    <td>
                      {
                        isCampoClinicoAutorizado(campoClinico.lugaresAutorizados) &&
                        campoClinico.estatus
                      }
                    </td>
                    <td>
                      {getActionButton(campoClinico)}
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
      </div>
    </div>
  )
}

export default DetalleSolicitudMultiple

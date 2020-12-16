import * as React from 'react'
import {getSchemeAndHttpHost, moneyFormat} from "../../../utils";
import {Fragment} from "react";
import DetalleMontoPago from "../DetalleMontoPago";

const DetalleCamposClinicos = ({ camposClinicos, solicitud }) => {

  function getTotalBySolicitud() {
    let total = 0.0;
    for(let camposClinico of camposClinicos) {
      total += parseFloat(camposClinico.montoPagar)
    }

    return moneyFormat(total)
  }

  function getMontoCarrera(id) {
    let montos = solicitud.montosCarreras.filter(elem => { return elem.carrera.id === id });
    return montos.length > 0 ? montos[0] : {};
  }

  console.log(camposClinicos);
  console.log(solicitud);

  return(
    <div className="panel panel-default">
      <div className="panel-body">
        <table className='table'>
          <thead className='headers'>
          <tr>
            <th>Sede</th>
            <th>Campo clínico</th>
            <th>Carrera</th>
            <th>No. de lugares solicitados</th>
            <th>No. de lugares autorizados</th>
            <th>Periodo</th>
            <th>No. de semanas</th>
            <th>Formato de cálculo de cuotas</th>
            <th>Monto a pagar por campo clínico</th>
          </tr>
          </thead>
          <tbody>
          {
            camposClinicos.map((campoClinico, index) =>
              <Fragment key={index}>
                <tr key={index}>
                  <td>{campoClinico.unidad.nombre}</td>
                  <td>{campoClinico.convenio.cicloAcademico.nombre}</td>
                  <td>{campoClinico.convenio.carrera.nivelAcademico.nombre} {campoClinico.convenio.carrera.nombre}</td>
                  <td>{campoClinico.lugaresSolicitados}</td>
                  <td>{campoClinico.lugaresAutorizados}</td>
                  <td>
                    {campoClinico.fechaInicial} - {campoClinico.fechaFinal}
                  </td>
                  <td>{campoClinico.numeroSemanas}</td>
                  <td>
                    <a
                      href={`${campoClinico.enlaceCalculoCuotas}`}
                      target='_blank'
                      download
                    >
                      Descargar
                    </a>
                  </td>
                  <td className='text-right'>{moneyFormat(campoClinico.montoPagar)}</td>
                </tr>
                <tr>
                  <td colSpan={9}>
                    <DetalleMontoPago
                      monto={getMontoCarrera(campoClinico.convenio.carrera.id)}
                      campoClinico={campoClinico}

                    />
                  </td>
                </tr>
              </Fragment>
            )
          }
          </tbody>
        </table>
        <hr/>
        <div className='row'>
          <div className="col-md-12 text-right">
            <p><strong>Monto total de la solicitud:</strong>&nbsp;&nbsp;&nbsp;{getTotalBySolicitud()}</p>
          </div>
        </div>
      </div>
    </div>
  )
}

export default DetalleCamposClinicos

import * as React from 'react'

const DetalleCamposClinicos = ({ camposClinicos }) => {

  function moneyFormat(monto) {
    const formatter = new Intl.NumberFormat('es-MX', {
      style: 'currency',
      currency: 'MXN',
    });

    return formatter.format(monto)
  }

  function getTotalBySolicitud() {
    let total = 0;
    for(let camposClinico of camposClinicos) {
      total += camposClinico.montoPagar
    }

    return moneyFormat(total)
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
            <th>No. de lugares solicitados</th>
            <th>No. de lugares autorizados</th>
            <th>Periodo</th>
            <th>No. de semanas</th>
            <th>Acciones</th>
            <th>Monto a pagar por campo clínico</th>
          </tr>
          </thead>
          <tbody>
          {
            camposClinicos.map((campoClinico, index) =>
              <tr key={index}>
                <td>{campoClinico.unidad.nombre}</td>
                <td>{campoClinico.convenio.cicloAcademico.nombre}</td>
                <td>{campoClinico.convenio.carrera.nivelAcademico.nombre} {campoClinico.convenio.carrera.nombre}</td>
                <td>{campoClinico.lugaresSolicitados}</td>
                <td>{campoClinico.lugaresAutorizados}</td>
                <td>
                  {new Date(campoClinico.fechaInicial).toLocaleDateString()} - {new Date(campoClinico.fechaFinal).toLocaleDateString()}
                </td>
                <td>{campoClinico.numeroSemanas}</td>
                <td>
                  <a
                    href={campoClinico.enlaceCalculoCuotas}
                    target='_blank'
                  >
                    Formato de calculo de cuotas
                  </a>
                </td>
                <td className='text-right'>{moneyFormat(campoClinico.montoPagar)}</td>
              </tr>
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

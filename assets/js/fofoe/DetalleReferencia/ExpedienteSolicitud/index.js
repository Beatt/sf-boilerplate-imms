import * as React from 'react'
import {getSchemeAndHttpHost} from "../../../utils";

const ExpedienteSolicitud = ({ solicitud, campos, pago }) => {

  const ComprobanteOficio = ({solicitud}) => {
    if(solicitud.fechaComprobanteFormatted)
      return (<a href={`${getSchemeAndHttpHost()}/fofoe/solicitud/${solicitud.id}/oficio`} target={'_blank'}>Descargar</a>);
    return (<></>);
  }

  const LinkFormatoFofoe = ({campo}) => {
    return (<a href={`${getSchemeAndHttpHost()}/fofoe/campo_clinico/${campo.id}/formato_fofoe/download`} target={'_blank'}>Formato
        FOFOE</a>);
  }

  const ExpedienteDetallado = ({solicitud}) => {
    return (
      <div className="table-responsive">
        <table className="table">
          <thead>
          <tr>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Archivo</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>Oficio de Montos de Colegiatura e Inscripción</td>
            <td>{solicitud.fechaComprobanteFormatted}</td>
            <td><ComprobanteOficio solicitud={solicitud}/></td>
          </tr>
          </tbody>
        </table>
      </div>
    )
  }

  console.log(campos);

  return(
    <div className='container'>
      <div className='row'>
        <div className='col-md-6 mt-20 mb-10'>
          <h2>Campos Clínicos</h2>
        </div>
      </div>
      <div className="table-responsive">
        <table className="table table-striped">
          <thead>
          <tr>
            <th>Sede </th>
            <th>Campo Clínico </th>
            <th>Nivel </th>
            <th>Carrera </th>
            <th>No. de lugares</th>
            <th>Período</th>
            <th> </th>
          </tr>
          </thead>
          <tbody>
          {campos.map(cc => {
            return (
              <tr key={cc.id}>
                <td>{cc.unidad.nombre}</td>
                <td>{cc.convenio.cicloAcademico.nombre}</td>
                <td>{cc.convenio.carrera.nivelAcademico.nombre}</td>
                <td>{cc.convenio.carrera.nombre}</td>
                <td>Autorizados {cc.lugaresAutorizados}</td>
                <td>Inicio {cc.fechaInicialFormatted} <br/> Final {cc.fechaInicialFormatted}</td>
                <td>
                  <LinkFormatoFofoe campo={cc}/> <br/>
                </td>
              </tr>
            )
          })}
          </tbody>
        </table>
      </div>
      <ExpedienteDetallado
        solicitud={solicitud}
      />
    </div>
  )

  {
  }

}

export default ExpedienteSolicitud;
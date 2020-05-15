import * as React from "react";
import './styles.scss'

const ListConvenios = ({ convenios }) => {
  return (
    <div className='list-convenios'>
      <div className='row flex align-items-center'>
        <div className='col-md-6'>
          <p className="mb-10 text-bold">Convenios vigentes de la institución educativa</p>
          <table className='table table-bordered'>
            <thead className='headers'>
            <tr>
              <th>Número</th>
              <th>Grado</th>
              <th>Ciclo</th>
              <th>Carrera</th>
              <th>Vigencia</th>
            </tr>
            </thead>
            <tbody>
            {
              convenios.map((item) => (
                <tr className={`label-${item.convenio.label}`}>
                  <td>{item.id}</td>
                  <td>{item.convenio.carrera.nivelAcademico.nombre}</td>
                  <td>{item.convenio.cicloAcademico.nombre}</td>
                  <td>{item.convenio.carrera.nombre}</td>
                  <td>{item.convenio.vigencia}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className='col-md-6'>
          <div className='tabla-vigencia'>
            <div className='tabla-vigencia__item'>
              <p className='tabla-vigencia__title'>Convenio está vigente, con fecha de término mayor a un año.</p>
            </div>
            <div className='tabla-vigencia__item'>
              <p className='tabla-vigencia__title'>Convenio vigente, con fecha de término menor a un año pero mayor a 6 meses. El convenio debería estar en trámites de renovación.</p>
            </div>
            <div className='tabla-vigencia__item'>
              <p className='tabla-vigencia__title'>La vigencia del convenio está por terminar, es necesario actualizar el convenio.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default ListConvenios

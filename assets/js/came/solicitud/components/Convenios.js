import * as React from 'react'
import './Convenios.scss';

const Convenios = (props) => {

    return (
        <div className={'list-convenios'}>
            <div className="col-md-12">
                <h3>Convenios vigentes de la Institución Educativa</h3>
            </div>
            <div className="col-md-6">
                <table className="table">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Grado</th>
                            <th>Ciclo</th>
                            <th>Carrera</th>
                            <th>Vigencia</th>
                        </tr>
                    </thead>
                    <tbody>
                    {props.convenios.map(convenio => {
                        if(convenio.carrera && convenio.cicloAcademico){
                            return (
                                <tr key={convenio.id} className={`label-${convenio.label}`}>
                                    <td>{convenio.nombre}</td>
                                    <td>{convenio.carrera.nivelAcademico.nombre}</td>
                                    <td>{convenio.cicloAcademico.nombre}</td>
                                    <td>{convenio.carrera.nombre}</td>
                                    <td>{convenio.vigencia}</td>
                                </tr>
                            )
                        }
                    })}
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
    )
}
export default Convenios;
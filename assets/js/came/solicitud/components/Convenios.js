import * as React from 'react'

const Convenios = (props) => {
    return (
        <>
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
                                <tr key={convenio.id}>
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
            <div className="col-md-6">

            </div>
        </>
    )
}
export default Convenios;
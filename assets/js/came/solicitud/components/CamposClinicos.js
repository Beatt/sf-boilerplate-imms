import * as React from 'react'

const CamposClinicos = (props) => {

    if(props.campos.length>0){
        return (
            <>
                <div className="col-md-12">
                    <h3>Información Solicitud Campos Clínicos</h3>
                    <h4>Campos Clínicos registrados en la Solicitud</h4>
                    <div className="table-responsive">
                        <table className="table">
                            <thead>
                            <tr>
                                <th>Ciclo Académico</th>
                                <th>Nivel</th>
                                <th>Carrera</th>
                                <th>Periodo</th>
                                <th>Sede</th>
                                <th>No. de Lugares Solicitados</th>
                                <th>No. de Lugares Autorizados</th>
                            </tr>
                            </thead>
                            <tbody>
                            {props.campos.map(cc => {
                                return (
                                    <tr key={cc.id}>
                                        <td>{cc.convenio.cicloAcademico.nombre}</td>
                                        <td>{cc.convenio.carrera.nivelAcademico.nombre}</td>
                                        <td>{cc.convenio.carrera.nombre}</td>
                                        <td>{cc.fechaInicialFormatted} - {cc.fechaFinalFormatted}</td>
                                        <td>{cc.unidad.nombre}</td>
                                        <td>{cc.lugaresSolicitados}</td>
                                        <td>{cc.lugaresAutorizados}</td>
                                    </tr>
                                )
                            })}
                            </tbody>
                        </table>
                    </div>
                </div>
            </>
        )
    }
    return <></>

}

export  default CamposClinicos;
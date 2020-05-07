import React from 'react';
import ReactDOM from 'react-dom'

const Registrar = ({
    autorizados, 
    camposClinicos, 
    institucion

}) => {

    let distinctCarreers = Array.from(new Set( camposClinicos.map(x => x.carrera) ))

    {console.log(distinctCarreers)}

    let uniques = Array.from(new Set( distinctCarreers.map(s => s.id)))
    .map(id => {
        return {
            id: id,
            nombre: distinctCarreers.find(s => s.id === id).nombre,
            nivelAcademico: distinctCarreers.find(s => s.id === id).nivelAcademico,
        };
    });

    {console.log(uniques)}

    return(
        <div className='row'>
            <div className="col-md-12 mt-10">
                <p>Se autorizaron {autorizados} Campos Clínicos para las carreras de 

                    {
                        uniques.map((item) => <strong> {item.nombre}, </strong>)
                    }

                </p>
            </div>

            <div className="col-md-8 mt-10">
                <p>Cargue el oficio que contenga los montos de inscripción de todas las carreras que comprenden su solicitud de campos clínicos </p>
            </div>

            <div className="col-md-4 mt-10">
                
            </div>

            <div className="col-md-12 mt-10">
                <p>Ingrese los montos correspondientes a cada carrera de su solicitud</p>
            </div>

            <div className="col-md-12 mt-20">
                <div className="panel panel-default">
                    <div className="panel-body">
                        <table className='table'>
                            <thead className='headers'>
                                <tr>
                                    <th>Nivel Académico</th>
                                    <th>Carrera</th>
                                    <th>Inscripción</th>
                                    <th>Colegiatura</th>
                                </tr>
                            </thead>
                            <tbody>
                            {
                                uniques.map((item, index) => {
                                return <tr key={index}>
                                    <td>{item.nivelAcademico.nombre}</td>
                                    <td>{item.nombre}</td>
                                    <td><input type='text' name='inscripcion[]' className='form-control'/></td>
                                    <td><input type='text' name='colegiatura[]' className='form-control'/></td>
                                </tr>
                                })
                            }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div className='col-md-12 mt-10'>
                        <p>La institución educativa <span className='text-bold'>{institucion}</span>, confirma que el oficion adjunto, contiene el monto correspondiente a
                        los montos de la colegiatura e inscripción por cada una de las carreras mencionadas anteriormente</p>
            </div>

        </div>
    )


}


ReactDOM.render( 
    <Registrar
    autorizados={window.AUTORIZADOS_PROP}
    camposClinicos = {window.CAMPOS_PROP}
    institucion = {window.INSTITUCION_PROP} />
,document.getElementById('registrar-component'));
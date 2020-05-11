import React from 'react';
import ReactDOM from 'react-dom'

const Registrar = ({
    autorizados,
    solicitudId, 
    institucion,
    carreras,
    solicitud,
    id

}) => {

    {console.log(carreras)}

    return(
        <div className='row'>
            <div className="col-md-12 mt-10">
                <p>Se autorizaron {autorizados} Campos Clínicos para las carreras de 

                    {
                        carreras.map((item) => <strong> {item.nombre}, </strong>)
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


            <form
            action={`/instituciones/${id}/solicitudes/${solicitudId}/registrar`}
            method="post"
            >
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
                                    solicitud.montosCarrera.map((item, index) => {
                                    return <tr key={index}>
                                        <td>Lincenciatura</td>
                                        <td>{item.nombre}</td>
                                        <td className='hidden'><input className='form-control' type="text" defaultValue={item.id} name='carreraid[]'/></td>
                                        <td><input className='form-control'
                                            type="text"
                                            name="montosCarrera[montoInscripcion]"
                                            id="montosCarrera_montoInscripcion"
                                            defaultValue={item.montoInscripcion}
                                            required={true}/>
                                        </td>
                                        <td><input className='form-control'
                                            type="text"
                                            name="montosColegiatura[montoColegiatura]"
                                            id="montosCarrera_montoColegiatura"
                                            defaultValue={item.montoColegiatura}
                                            required={true}/>
                                        </td>
                                    </tr>
                                    })
                                }
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <button type="submit">Enviar</button>
            </form>

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
    institucion = {window.INSTITUCION_PROP}
    carreras = {window.CARRERAS_PROP}
    solicitud = {window.MONTOS_PROP}
    solicitudId = {window.SOLICITUD_PROP}
    id = {window.ID_PROP}/>
,document.getElementById('registrar-component'));
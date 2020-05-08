import * as React from 'react'
import Loader from "../../components/Loader/Loader";

const SolicitudValidaMontos = (props) => {

    const [isLoading, setIsLoading] = React.useState(false);

    const callbackIsLoading = (value) => {
        setIsLoading(value);
    }

    const handleSolicitudValidaMontos = (event) => {
        event.preventDefault();
        setIsLoading(true);
    }

    return (
        <>
            <Loader show={isLoading}/>
            <Loader show={isLoading}/>
            <div className="col-md-12">
                <p><strong>No. de Solicitud:</strong> {props.solicitud.noSolicitud}</p>
            </div>
            <div className="col-md-12">
                <p><strong>Estado:</strong> {props.solicitud.estatusCameFormatted}</p>
            </div>
            <div className="col-md-12">
                <p><strong>Insitución Educativa:</strong> {props.solicitud.institucion.nombre}</p>
            </div>
            <div className="col-md-12">
                <p><strong>Por favor valide los que los montos que se muestran a continuación coincidan con los reportados en el oficio</strong></p>
            </div>
            <div className="col-md-12">
                <p>Oficio: {props.solicitud.documento}</p>
            </div>
            <div className="col-md-12">
                <p>Valide los montos correspondientes a cada carrera de su solicitud</p>
            </div>
            <form onSubmit={handleSolicitudValidaMontos}>
                <div className="col-md-3"/>
                <div className="col-md-6">
                    <table className="table">
                        <thead>
                        <tr>
                            <th>Nivel</th>
                            <th>Carrera</th>
                            <th>Inscripción</th>
                            <th>Colegiatura</th>
                        </tr>
                        </thead>
                        <tbody>
                        {props.solicitud.montos.map(monto =>{
                            return (
                                <tr key={monto.id}>
                                    <td>{monto.carrera.nivelAcademico.nombre}</td>
                                    <td>{monto.carrera.nombre}</td>
                                    <td><input type="number"/></td>
                                    <td><input type="number"/></td>
                                </tr>
                            )
                        })}
                        </tbody>
                    </table>
                </div>
                <div className="col-md-3"/>
                <div className="col-md-12">
                    <label htmlFor="radio_solicitud">¿Todos los montos corresponden?</label>
                </div>
                <div className="col-md-12">
                    <input name={'radio_solicitud'} type={'radio'} value={true} id={'radio1'}/>Si
                    &#160;&#160;&#160;&#160;
                    <input name={'radio_solicitud'} type={'radio'} value={false} id={'radio2'} checked={true}/>No
                </div>
                <div className="col-md-12">
                    <label htmlFor="observaciones_solicitud">Observaciones</label>
                    <textarea className={'form-control'} placeholder={'Observaciones'}/>
                </div>
                <div className="col-md-8"/>
                <div className="col-md-4">
                    <label htmlFor="btn_solicitud">&#160;</label>
                    <button id="btn_solicitud" className={'form-control btn btn-primary'}>Guardar</button>
                </div>
            </form>
        </>
    )
}

export default SolicitudValidaMontos;
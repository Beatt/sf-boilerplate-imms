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
                <p>Oficio: {solicitud.documento}</p>
            </div>
            <div className="col-md-12">
                <p>Valie los montos correspondientes a cada carrera de su solicitud</p>
            </div>
            <form onSubmit={handleSolicitudValidaMontos}>
                <div className="col-md-3"/>
                <div className="col-md-6">
                    <table className="table">
                        <thead>
                        <tr>
                            <th/>
                            <th>Carrera</th>
                            <th>Inscripción</th>
                            <th>Colegiatura</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div className="col-md-3"/>
            </form>
        </>
    )
}

export default SolicitudValidaMontos;
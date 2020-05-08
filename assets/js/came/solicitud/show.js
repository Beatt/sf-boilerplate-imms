import * as React from 'react'
import Loader from "../../components/Loader/Loader";

const SolicitudShow = (props) => {

    const [isLoading, setIsLoading] = React.useState(false);

    const callbackIsLoading = (value) => {
        setIsLoading(value);
    }

    return (
        <>
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
                <p>Se <strong>autorizaron</strong> {props.solicitud.camposClinicosAutorizados} de {props.solicitud.camposClinicosSolicitados} Campos Clinicos solicitados.</p>
            </div>
            <div className="col-md-8"/>
            <div className="col-md-4">
                <div className="input-group">
                    <input type="text" className="form-control" placeholder="Buscar" name="search" />
                    <div className="input-group-btn">
                        <button className="btn btn-default" type="submit">
                            <i className="glyphicon glyphicon-search"/>
                        </button>
                    </div>
                </div>
            </div>
            <div className="col-md-12">
                <table className="table">
                    <thead>
                    <tr>
                        <th>Sede</th>
                        <th>Campo Clínico</th>
                        <th>Nivel</th>
                        <th>Carrera</th>
                        <th>No. de lugares solicitados</th>
                        <th>No. de lugares autorizados</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Término</th>
                    </tr>
                    </thead>
                    <tbody>
                    {props.solicitud.campoClinicos.map(cc => {
                        return (
                            <tr key={cc.id}>
                                <td>{cc.unidad.nombre}</td>
                                <td>{cc.convenio.cicloAcademico.nombre}</td>
                                <td>{cc.convenio.carrera.nivelAcademico.nombre}</td>
                                <td>{cc.convenio.carrera.nombre}</td>
                                <td>{cc.lugaresSolicitados}</td>
                                <td>{cc.lugaresAutorizados}</td>
                                <td>{cc.fechaInicial}</td>
                                <td>{cc.fechaFinal}</td>
                            </tr>
                        )
                    })}
                    </tbody>
                </table>
            </div>
            <div className="col-md-6">
                <h2>Expediente</h2>
            </div>
            <div className="col-md-6">
                <h2>Convenios</h2>
            </div>
            <div className="col-md-6">
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
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Comprobante de Pago</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Factura (CFDI)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
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
                    {props.solicitud.convenios.map((convenio, i) => {
                        return (
                            <tr key={i}>
                                <td>{convenio.numero}</td>
                                <td>{convenio.nivelAcademico}</td>
                                <td>{convenio.cicloAcademico}</td>
                                <td>{convenio.carrera}</td>
                                <td>{convenio.vigencia}</td>
                            </tr>
                        )
                    })}
                    </tbody>
                </table>
            </div>
        </>
    )
}

export default SolicitudShow;
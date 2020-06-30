import * as React from 'react';
import Institucion from './components/Institucion';
import Convenios from './components/Convenios';
import CampoClinicoForm from './components/CampoClinicoForm';
import CamposClinicos from './components/CamposClinicos';
import Loader from '../../components/Loader/Loader'

const SolicitudEdit = (props) => {

    const [isLoading, setIsLoading] = React.useState(false);
    const [camposClinicos, setCamposClinicos] = React.useState(props.solicitud.campoClinicos)

    const callbackIsLoading = (value) => {
        setIsLoading(value);
    }

    const callbackCampoClinico = (campo) => {
        const campos = camposClinicos.slice(0);
        campos.push(campo);
        setCamposClinicos(campos);
    }

    const handleSolicitudSubmit = (event) => {
        event.preventDefault();
        setIsLoading(true);
        fetch('/came/api/solicitud/terminar/' + props.solicitud.id, {
            method: 'post'
        }).then(response => {
            return response.json()
        }, error => {
            console.error(error);
        }).then(json => {
            if (json.status) {
                new Promise((resolve, reject) => {
                    setTimeout(() => {
                        resolve()
                    }, 250)
                }).then(() => {
                    document.location.href = '/came/solicitud';
                });
            }
        }).finally(() => {
            setIsLoading(false);
        });
    }

    const isInstitucionComplete = () => {
        return props.solicitud.institucion.id &&
            props.solicitud.institucion.representante && props.solicitud.institucion.correo
            && props.solicitud.institucion.rfc && props.solicitud.institucion.direccion
            && props.solicitud.institucion.telefono;
    }

    const callbackFunction = (institucion) => {
        const institucion_o = Object.assign(props.solicitud.institucion, institucion);
        props.solicitud = Object.assign(props.solicitud, institucion_o);
    }

    const handleDeleteEvent = (campo) => {
        setIsLoading(true);
        fetch(`/came/api/campo_clinico/${campo}`, {
            method: 'delete'
        }).then(response => {
            return response.json()
        }, error => {
            console.error(error);
        }).then(json => {
            if (json.status) {
                removeCampo(campo);
            }
        }).finally(() => {
            setIsLoading(false);
        });
    }

    const removeCampo = (campo) => {

        const nuevos = [];
        camposClinicos.map(item => {
            if (item.id.toString() !== campo.toString()) {
                nuevos.push(item);
            }
        })
        setCamposClinicos(nuevos);
    }

    return (
        <>
            <Loader show={isLoading}/>
            <div className="col-md-12">
                <h2>Información Institución</h2>
            </div>
            <Institucion
                instituciones={props.instituciones}
                callbackIsLoading={callbackIsLoading}
                parentCallback={callbackFunction}
                disableSelect={true}
                institucion={props.solicitud.institucion}
            />
            <Convenios convenios={props.solicitud.institucion.convenios}/>
            <div style={{display: (isInstitucionComplete() ? 'block' : 'none')}}>
                <CamposClinicos
                    campos={camposClinicos}
                    handleDelete={campo => {
                        handleDeleteEvent(campo)
                    }}
                />
                <CampoClinicoForm
                    unidades={props.unidades}
                    solicitud={props.solicitud}
                    convenios={props.solicitud.institucion.convenios}
                    callbackCampoClinico={callbackCampoClinico}
                    callbackIsLoading={callbackIsLoading}
                />
                <form onSubmit={handleSolicitudSubmit}>
                    <div className="row">
                        <div className="col-md-12">
                            <label htmlFor="btn_solicitud">&#160;</label>
                            <button id="btn_solicitud" className={'form-control btn btn-success'}>Terminar Solicitud
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div style={{display: (isInstitucionComplete() ? 'none' : 'block')}}>
                <div className={`alert alert-warning `}>
                    Es necesario capturar la información de la institución para poder modificar la solicitud
                </div>
            </div>
        </>
    );
}

export default SolicitudEdit;
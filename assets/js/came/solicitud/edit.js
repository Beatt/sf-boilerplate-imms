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
        fetch('/api/solicitud/terminar/' + props.solicitud.id , {
            method: 'post'
        }).then(response => {
            return response.json()
        }, error => {
            console.error(error);

        }).then(json => {

        }).finally(() => {
            setIsLoading(false);
            document.location.href = '/solicitud';
        });
    }

    return (
        <>
            <Loader show={isLoading}/>
            <div className="col-md-12">
                <h2>Información Institución</h2>
            </div>
            <Institucion
                instituciones={props.instituciones}
                callbackIsLoading = {callbackIsLoading}
                disableSelect={true}
                institucion={props.solicitud.institucion}
            />
            <Convenios convenios={props.solicitud.institucion.convenios}/>
            <CamposClinicos campos={camposClinicos} />
            <CampoClinicoForm
                unidades={props.unidades}
                convenios={props.solicitud.institucion.convenios}
                callbackCampoClinico = {callbackCampoClinico}
                callbackIsLoading = {callbackIsLoading}
            />
            <form onSubmit={handleSolicitudSubmit}>
                <div className="col-md-12">
                    <label htmlFor="btn_solicitud">&#160;</label>
                    <button id="btn_solicitud" className={'form-control btn btn-success'}>Terminar Solicitud</button>
                </div>
            </form>
        </>
    );
}

export default SolicitudEdit;
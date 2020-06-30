import * as React from 'react'

const SolicitudAccion = (props) => {

    const Editar = () => {
        return (<a href={`/came/solicitud/${props.solicitud.id}/edit`}>Editar</a>);
    }

    const ValidarMontos = () => {
        return (<a href={`/came/solicitud/${props.solicitud.id}/validar_montos`}>Validar Montos</a>);
    }

    let result = (<></>);
    switch(props.solicitud.estatus){
        case 'Solicitud creada':
            result = (<Editar/>)
            break;
        case 'En validación de montos CAME':
            result = (<ValidarMontos/>);
            break;
    }
    return result;

}

export default SolicitudAccion;
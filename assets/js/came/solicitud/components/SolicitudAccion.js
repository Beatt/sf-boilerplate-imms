import * as React from 'react'

const SolicitudAccion = (props) => {

    const Editar = () => {
        return (<a href={`/solicitud/${props.solicitud.id}/edit`}>Editar</a>);
    }

    const ValidarMontos = () => {
        return (<a href={`/solicitud/${props.solicitud.id}/validar_montos`}>Validar Montos</a>);
    }

    const FormatoFoFoe = () => {
        return (<a href={`/solicitud`}>Ver Formato FOFOE</a>);
    }

    const DescargarCredenciales = () => {
        return (<a href={`/solicitud`}>Descargar Credenciales</a>);
    }

    const VerComprobante = () => {
        return (<a href={'/solicitud'}>Ver Comprobante Pago</a>)
    }

    let result = (<></>);
    switch(props.solicitud.estatus){
        case 'Solicitud creada':
            result = (<Editar/>)
            break;
        case 'En validacion de montos':
            result = (<ValidarMontos />)
            break;
        case 'Montos Validados':
            result = (<FormatoFoFoe />)
            break;
        case 'Pago validado':
            result = (<DescargarCredenciales />)
            break;
        case '':
            result = (<VerComprobante />)
            break;        
    }
    return result;

}

export default SolicitudAccion;
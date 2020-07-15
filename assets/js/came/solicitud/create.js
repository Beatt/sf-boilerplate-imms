import * as React from 'react';
import Institucion from './components/Institucion';
import Convenios from './components/Convenios';
import CampoClinicoForm from './components/CampoClinicoForm';
import CamposClinicos from './components/CamposClinicos';
import Loader from '../../components/Loader/Loader'

const SolicitudCreate = (props) => {

    const [selectedInstitution, setselectedInstitution] = React.useState({});
    const [camposClinicos, setCamposClinicos] = React.useState([])
    const [isLoading, setIsLoading] = React.useState(false)
    const [solicitud, setSolicitud] = React.useState(null);
    const [convenios, setConvenios] = React.useState([]);

    const callbackFunction = (institucion) => {
        setselectedInstitution(institucion);
    }

    const callbackCampoClinico = (campo) => {
        const campos = camposClinicos.slice(0);
        campos.push(campo);
        setCamposClinicos(campos);
    }

    const callbackIsLoading = (value) => {
        setIsLoading(value);
    }

    const handleSolicitudSubmit = (event) => {
        event.preventDefault();
        setIsLoading(true);

        fetch('/came/api/solicitud/terminar/' + solicitud.id , {
            method: 'post'
        }).then(response => {
            return response.json()
        }, error => {
            console.error(error);
        }).then(json => {
            if(json.status){
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
        return selectedInstitution.id &&
            selectedInstitution.representante && selectedInstitution.correo
            && selectedInstitution.rfc && selectedInstitution.direccion
            && selectedInstitution.telefono;
    }

    return (
      <>
          <Loader show={isLoading}/>
          <div className="row">
              <div className="col-md-12">
                  <h2>Información Institución</h2>
                  <h3>Ingrese la información correspondiente a la institución Educativa que solicita el campo</h3>
              </div>
          </div>
          <Institucion
              instituciones={props.instituciones}
              parentCallback = {callbackFunction}
              callbackIsLoading = {callbackIsLoading}
              disableSelect={!!solicitud}
              conveniosCallback={result => setConvenios(result)}
          />
          <div style={{display : (selectedInstitution.id ? 'block': 'none')}}>
          <Convenios convenios={convenios? convenios : []}/>
          </div>
          <div style={{display : (isInstitucionComplete()? 'block' : 'none')}}>
              <CamposClinicos campos={camposClinicos} />
              <CampoClinicoForm
                  unidades={props.unidades}
                  convenios={convenios ? convenios: []}
                  callbackCampoClinico = {callbackCampoClinico}
                  callbackIsLoading = {callbackIsLoading}
                  callbackSolicitud = {value => setSolicitud(value)}
              />
              <form onSubmit={handleSolicitudSubmit} style={{display : (solicitud && camposClinicos.length > 0 ? 'block' : 'none')}}>
                  <div className="row">
                      <div className="col-md-12">
                          <label htmlFor="btn_solicitud">&#160;</label>
                          <button id="btn_solicitud" className={'form-control btn btn-success'}>Terminar Solicitud</button>
                      </div>
                  </div>
              </form>
          </div>
          <div className={'col-md-12'}>
              <div style={{display : (isInstitucionComplete()? 'none' : 'block')}}>
                  <div className={`alert alert-warning `}>
                      Es necesario capturar la información de la institución para poder crear una nueva solicitud
                  </div>
              </div>
          </div>
      </>
    );
};

export default SolicitudCreate;
import * as React from 'react'
import Institucion from './components/Institucion'
import Convenios from './components/Convenios'
import CampoClinicoForm from './components/CampoClinicoForm';
import CamposClinicos from './components/CamposClinicos';

const SolicitudCreate = (props) => {

    const [selectedInstitution, setselectedInstitution] = React.useState({});
    const [camposClinicos, setCamposClinicos] = React.useState([])

    const callbackFunction = (institucion) => {
        setselectedInstitution(institucion);
    }

    const CSection = () => {
        if(selectedInstitution.id){
            return (
                <>
                    <Convenios convenios={selectedInstitution.convenios ? selectedInstitution.convenios: []}/>
                    <CamposClinicos campos={camposClinicos} />
                    <CampoClinicoForm
                        unidades={props.unidades}
                        convenios={selectedInstitution.convenios ? selectedInstitution.convenios: []}/>
                </>
            );
        }
        return (<></>)
    }

    return (
      <>
          <h3>Información Insitución</h3>
          <p>Ingrese la información correspondiente a la institución Educativa que solicita el campo</p>
          <Institucion
              instituciones={props.instituciones}
              parentCallback = {callbackFunction}
          />
          <CSection/>
      </>
    );
};

export default SolicitudCreate;
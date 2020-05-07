import * as React from 'react'

const Institucion = (props) => {

    const [selectedInstitution, setselectedInstitution] = React.useState({});

    const handleSelectedInstitution = (institucion) => {
        setselectedInstitution(institucion);
        props.parentCallback(institucion);
    }

    let index = 0;

    const IFields = () => {
        if(selectedInstitution.id){
            return (
                <>
                    <div className="col-md-3">
                        <label htmlFor="">RFC:</label>
                        <input type="text" defaultValue={selectedInstitution.rfc?selectedInstitution.rfc:''}/>
                    </div>
                    <div className="col-md-9">
                        <label htmlFor="">Domicilio:</label>
                        <input type="text" defaultValue={selectedInstitution.domicilio?selectedInstitution.domicilio:''}/>
                    </div>
                    <div className="col-md-3">
                        <label htmlFor="">Número teléfonico:</label>
                        <input type="text" defaultValue={selectedInstitution.rfc?selectedInstitution.rfc:''}/>
                    </div>
                    <div className="col-md-9">
                        <label htmlFor="">Página web (Opcional):</label>
                        <input type="text" defaultValue={selectedInstitution.domicilio?selectedInstitution.domicilio:''}/>
                    </div>
                    <div className="col-md-3">
                        <label htmlFor="">Correo electrónico:</label>
                        <input type="email" defaultValue={selectedInstitution.rfc?selectedInstitution.rfc:''}/>
                    </div>
                    <div className="col-md-3">
                        <label htmlFor="">Número de fax (Opcional):</label>
                        <input type="text" defaultValue={selectedInstitution.domicilio?selectedInstitution.domicilio:''}/>
                    </div>
                    <div className="col-md-3">
                        <button>Guardar Institución Educativa</button>
                    </div>
                </>
            );
        }
        return (<></>);
    }

    return (
        <>
            <form action="">
                <div className="col-md-12">
                    <label htmlFor="institucion_name">Nombre de la institución:</label>
                    <select name="institucion_name"
                            id="institucion_name"
                            onChange={e => handleSelectedInstitution(e.target.value ? props.instituciones[e.target.value] : {}) }
                            required={true}>
                        <option value="">Seleccionar ...</option>
                        {props.instituciones.map(institucion => {
                            return (
                                <option key={institucion.id} value={index++}>{institucion.nombre}</option>
                            )
                        })}
                    </select>
                    {/*<p><strong>Seleccionada: </strong> {selectedInstitution.nombre}</p>*/}
                </div>
                <IFields />
            </form>
        </>
    )
}

export default Institucion;
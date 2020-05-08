import * as React from 'react'

const Institucion = (props) => {

    const [selectedInstitution, setSelectedInstitution] = React.useState(props.institucion ? props.institucion : {});

    const [rfc, setRfc] = React.useState(props.institucion && props.institucion.rfc ? props.institucion.rfc : '');
    const [domicilio, setDomicilio] = React.useState(props.institucion && props.institucion.direccion ? props.institucion.direccion : '');
    const [phone, setPhone] = React.useState(props.institucion && props.institucion.telefono ? props.institucion.telefono : '');
    const [web, setWeb] = React.useState(props.institucion && props.institucion.sitioWeb ? props.institucion.sitioWeb : '' );
    const [email, setEmail] = React.useState(props.institucion && props.institucion.correo ? props.institucion.correo : '');
    const [fax, setFax] = React.useState(props.institucion && props.institucion.fax ? props.institucion.fax : '');

    const handleSelectedInstitution = (value) => {
        const results = props.instituciones.filter(item => {return value.toString() === item.id.toString()});
        const institucion = results.length > 0 ? results[0]: {};
        setSelectedInstitution(institucion);
        setRfc(institucion.rfc ? institucion.rfc : '');
        setDomicilio(institucion.direccion ? institucion.direccion : '');
        setPhone(institucion.telefono? institucion.telefono : '');
        setWeb(institucion.sitioWeb ? institucion.sitioWeb: '');
        setEmail(institucion.correo? institucion.correo :'');
        setFax(institucion.fax ? institucion.fax : '');
        props.parentCallback(institucion);
        return institucion ? institucion : {};
    }

    const handleUpdateInstitucion = (event) => {
        event.preventDefault();
        props.callbackIsLoading(true);
        let data = new FormData();
        data.append('institucion[rfc]', rfc);
        data.append('institucion[direccion]', domicilio);
        data.append('institucion[correo]', email);
        data.append('institucion[fax]', fax);
        data.append('institucion[sitioWeb]', web);
        data.append('institucion[telefono]', phone);
        fetch('/api/came/institucion/' + selectedInstitution.id, {
            method: 'post',
            body: data
        }).then(response => response.json(), error => {
            console.error(error);
        }).then(json => {
            console.log(json);
        }).finally(() => {
            props.callbackIsLoading(false)
        });
    }

    return (
        <>
            <form onSubmit={handleUpdateInstitucion}>
                <div className="col-md-12">
                    <label htmlFor="institucion_name">Nombre de la institución:</label>
                    <select name="institucion_name"
                            id="institucion_name"
                            className={'form-control'}
                            value={selectedInstitution.id ? selectedInstitution.id : ''}
                            onChange={e => handleSelectedInstitution(e.target.value) }
                            required={true}
                            disabled={props.disableSelect}
                    >
                        <option value="">Seleccionar ...</option>
                        {props.instituciones.map(institucion => {
                            return (
                                <option key={institucion.id} value={institucion.id}>{institucion.nombre}</option>
                            )
                        })}
                    </select>
                    {/*<p><strong>Seleccionada: </strong> {selectedInstitution.nombre}</p>*/}
                </div>
                <div style={{display : (selectedInstitution.id ? 'block' : 'none')}}>
                    <div className="col-md-3">
                        <label htmlFor="rfc">RFC:</label>
                        <input id="rfc"
                               className={'form-control'}
                               required={true}
                               type="text"
                               value={rfc}
                               onChange={e => setRfc(e.target.value)}
                        />
                    </div>
                    <div className="col-md-9">
                        <label htmlFor="domicilio">Domicilio:</label>
                        <input id={'domicilio'} className={'form-control'}
                               required={true}
                               type="text" value={domicilio} onChange={e => setDomicilio(e.target.value)}/>
                    </div>
                    <div className="col-md-3">
                        <label htmlFor="phone">Número teléfonico:</label>
                        <input id={'phone'}  className={'form-control'}
                               required={true}
                               type="text" value={phone} onChange={e => setPhone(e.target.value)}/>
                    </div>
                    <div className="col-md-9">
                        <label htmlFor="web_page">Página web (Opcional):</label>
                        <input id="web_page" className={'form-control'}
                               type="text" value={web} onChange={e => setWeb(e.target.value)}/>
                    </div>
                    <div className="col-md-3">
                        <label htmlFor="email">Correo electrónico:</label>
                        <input id="email" className={'form-control'}
                               required={true}
                               type="email" value={email} onChange={e => setEmail(e.target.value)}/>
                    </div>
                    <div className="col-md-3">
                        <label htmlFor="fax">Número de fax (Opcional):</label>
                        <input id="fax" className={'form-control'}
                               type="text" value={fax} onChange={e => setFax(e.target.value)}/>
                    </div>
                    <div className="col-md-3">
                        <label htmlFor="btn_institucion">&#160;</label>
                        <button id="btn_institucion" className={'form-control btn btn-primary'}>Guardar Institución Educativa</button>
                    </div>
                </div>
            </form>
        </>
    )
}

export default Institucion;
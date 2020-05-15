import * as React from 'react'

const Institucion = (props) => {

    const [selectedInstitution, setSelectedInstitution] = React.useState(props.institucion ? props.institucion : {});

    const [rfc, setRfc] = React.useState(props.institucion && props.institucion.rfc ? props.institucion.rfc : '');
    const [domicilio, setDomicilio] = React.useState(props.institucion && props.institucion.direccion ? props.institucion.direccion : '');
    const [phone, setPhone] = React.useState(props.institucion && props.institucion.telefono ? props.institucion.telefono : '');
    const [web, setWeb] = React.useState(props.institucion && props.institucion.sitioWeb ? props.institucion.sitioWeb : '');
    const [email, setEmail] = React.useState(props.institucion && props.institucion.correo ? props.institucion.correo : '');
    const [fax, setFax] = React.useState(props.institucion && props.institucion.fax ? props.institucion.fax : '');

    const [errores, setErrores] = React.useState({});
    const [alert, setAlert] = React.useState({});

    const handleSelectedInstitution = (value) => {
        const results = props.instituciones.filter(item => {
            return value.toString() === item.id.toString()
        });
        const institucion = results.length > 0 ? results[0] : {};
        setSelectedInstitution(institucion);
        setRfc(institucion.rfc ? institucion.rfc : '');
        setDomicilio(institucion.direccion ? institucion.direccion : '');
        setPhone(institucion.telefono ? institucion.telefono : '');
        setWeb(institucion.sitioWeb ? institucion.sitioWeb : '');
        setEmail(institucion.correo ? institucion.correo : '');
        setFax(institucion.fax ? institucion.fax : '');
        props.callbackIsLoading(true);
        fetch('/api/came/convenio/' + institucion.id)
            .then(response => {
                return response.json()}, error => {
                console.error(error)})
            .then(json => props.conveniosCallback(json.data))
            .finally(() => {props.callbackIsLoading(false);});
        props.parentCallback(institucion);
        return institucion ? institucion : {};
    }

    const handleUpdateInstitucion = (event) => {
        event.preventDefault();
        setErrores({});
        setAlert({});
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
            if (json.errors) {
                setErrores(json.errors);
            }
            setAlert(Object.assign(alert, {
                show: true,
                message: json.message,
                type: (json.status ? 'success' : 'danger')
            }))
        }).finally(() => {
            props.callbackIsLoading(false)
        });
    }

    return (
        <>
            <form onSubmit={handleUpdateInstitucion}>
                <div className="row">
                    <div className="col-md-12">
                        <div className={`form-group ${errores.institucion ? 'has-error has-feedback' : ''}`}>
                            <div className={`alert alert-${alert.type} `}
                                 style={{display: (alert.show ? 'block' : 'none')}}>
                                <a className="close" onClick={e => setAlert({})}>&times;</a>
                                {alert.message}
                            </div>
                            <label htmlFor="institucion_name">Nombre de la institución:</label>
                            <select name="institucion_name"
                                    id="institucion_name"
                                    className={'form-control'}
                                    value={selectedInstitution.id ? selectedInstitution.id : ''}
                                    onChange={e => handleSelectedInstitution(e.target.value)}
                                    required={true}
                                    disabled={props.disableSelect}
                            >
                                <option value="">Seleccionar ...</option>
                                {props.instituciones.map(institucion => {
                                    return (
                                        <option key={institucion.id}
                                                value={institucion.id}>{institucion.nombre}</option>
                                    )
                                })}
                            </select>
                            <span className="help-block">{errores.institucion ? errores.institucion[0] : ''}</span>
                            {/*<p><strong>Seleccionada: </strong> {selectedInstitution.nombre}</p>*/}
                        </div>
                    </div>
                </div>

                <div style={{display: (selectedInstitution.id ? 'block' : 'none')}}>
                    <div className="row">
                        <div className="col-md-3">
                            <div className={`form-group ${errores.rfc ? 'has-error has-feedback' : ''}`}>
                                <label className="control-label" htmlFor="rfc">RFC:</label>
                                <input id="rfc"
                                       className={'form-control'}
                                       required={true}
                                       type="text"
                                       value={rfc}
                                       onChange={e => setRfc(e.target.value)}
                                />
                                <span className="help-block">{errores.rfc ? errores.rfc[0] : ''}</span>
                            </div>
                        </div>
                        <div className="col-md-9">
                            <div className={`form-group ${errores.direccion ? 'has-error has-feedback' : ''}`}>
                                <label htmlFor="domicilio">Domicilio:</label>
                                <input id={'domicilio'} className={'form-control'}
                                       required={true}
                                       type="text" value={domicilio} onChange={e => setDomicilio(e.target.value)}/>
                                <span className="help-block">{errores.direccion ? errores.direccion[0] : ''}</span>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-md-3">
                            <div className={`form-group ${errores.telefono ? 'has-error has-feedback' : ''}`}>
                                <label htmlFor="phone">Número teléfonico:</label>
                                <input id={'phone'} className={'form-control'}
                                       required={true}
                                       type="text" value={phone} onChange={e => setPhone(e.target.value)}/>
                                <span className="help-block">{errores.telefono ? errores.telefono[0] : ''}</span>
                            </div>
                        </div>
                        <div className="col-md-9">
                            <div className={`form-group ${errores.sitioWeb ? 'has-error has-feedback' : ''}`}>
                                <label htmlFor="web_page">Página web (Opcional):</label>
                                <input id="web_page" className={'form-control'}
                                       type="text" value={web} onChange={e => setWeb(e.target.value)}/>
                                <span className="help-block">{errores.sitioWeb ? errores.sitioWeb[0] : ''}</span>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-md-3">
                            <div className={`form-group ${errores.correo ? 'has-error has-feedback' : ''}`}>
                                <label htmlFor="email">Correo electrónico:</label>
                                <input id="email" className={'form-control'}
                                       required={true}
                                       type="email" value={email} onChange={e => setEmail(e.target.value)}/>
                                <span className="help-block">{errores.correo ? errores.correo[0] : ''}</span>
                            </div>
                        </div>
                        <div className="col-md-3">
                            <div className={`form-group ${errores.fax ? 'has-error has-feedback' : ''}`}>
                                <label htmlFor="fax">Número de fax (Opcional):</label>
                                <input id="fax" className={'form-control'}
                                       type="text" value={fax} onChange={e => setFax(e.target.value)}/>
                                <span className="help-block">{errores.fax ? errores.fax[0] : ''}</span>
                            </div>
                        </div>
                        <div className="col-md-3">
                            <label htmlFor="btn_institucion">&#160;</label>
                            <button id="btn_institucion" className={'form-control btn btn-primary'}>Guardar Institución
                                Educativa
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </>
    )
}

export default Institucion;
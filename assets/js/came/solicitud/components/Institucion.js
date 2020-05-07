import * as React from 'react'

const Institucion = (props) => {

    const [selectedInstitution, setselectedInstitution] = React.useState({});

    const [rfc, setRfc] = React.useState('');
    const [domicilio, setDomicilio] = React.useState('');
    const [phone, setPhone] = React.useState('');
    const [web, setWeb] = React.useState('');
    const [email, setEmail] = React.useState('');
    const [fax, setFax] = React.useState('');

    const handleSelectedInstitution = (institucion) => {
        setselectedInstitution(institucion);
        setRfc(institucion.rfc ? institucion.rfc : '');
        setDomicilio(institucion.domicilio ? institucion.domicilio : '');
        setPhone(institucion.telefono? institucion.telefono : '');
        setWeb(institucion.sitioWeb ? institucion.sitioWeb: '');
        setEmail(institucion.correo? institucion.correo :'');
        setFax(institucion.fax ? institucion.fax : '');
        props.parentCallback(institucion);
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

    let index = 0;

    return (
        <>
            <form onSubmit={handleUpdateInstitucion}>
                <div className="col-md-12">
                    <label htmlFor="institucion_name">Nombre de la institución:</label>
                    <select name="institucion_name"
                            id="institucion_name"
                            className={'form-control'}
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
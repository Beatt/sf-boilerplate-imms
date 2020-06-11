import * as React from 'react'
import ReactDOM from 'react-dom'
import SolicitudCreate from './create';
import SolicitudEdit from './edit';
import SolicitudAccion from "./components/SolicitudAccion";
import SolicitudShow from "./show";
import SolicitudValidaMontos from "./validaMontos";
import Loader from "../../components/Loader/Loader";
import ReactPaginate from 'react-paginate';

const CameTableExample = (props) => {
    return (
        <>
            <form action="/solicitud" method="get">
                <label htmlFor="">Filtro</label><input name={'solicitudNo'} type="text"/>
                <button type={'submit'}>Enviar</button>
            </form>
            <div className="panel panel-default">
                <table className="table">
                    <thead>
                    <tr>
                        <td>Head1</td>
                        <td>Head2</td>
                        <td>Head3</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Col1</td>
                        <td>Col2</td>
                        <td>Col3</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </>
    );
}

class ExampleForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            fecha: '',
        };
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();
        let data = new FormData();
        data.append('token', this.props.token);
        fetch(this.props.action, {
            method: this.props.method,
            body: data,
        }).then(res => res.json()).then(json => {
            alert("Response: " + json.message);
        }).catch(error => alert('Error server: ' + error));
    }

    render() {
        return (
            <>
                <hr/>
                <form onSubmit={this.handleSubmit}>
                    <div className="form-group row">
                        <label htmlFor="text" className="col-4 col-form-label">Fecha Text Field</label>
                        <div className="col-8">
                            <div className="input-group">
                                <div className="input-group-prepend">
                                    <div className="input-group-text">
                                        <i className="fa fa-address-card"></i>
                                    </div>
                                </div>
                                <input id="text" type="date" className="form-control" value={this.state.value}/>
                            </div>
                        </div>
                    </div>
                    <p>{this.props.token}</p>
                    <div className="form-group row">
                        <div className="offset-4 col-8">
                            <button name="submit" type="submit" className="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </>
        )
    }
}

const SolicitudIndex = (props) => {

    const [solicitudes, setSolicitudes] = React.useState(props.solicitudes ? props.solicitudes : [])
    const [isLoading, setIsLoading] = React.useState(false)
    const [meta, setMeta] = React.useState(props.meta);
    const [query, setQuery] = React.useState({});

    const handleSearchEvent = () => {
        setIsLoading(true);
        let querystring = '';
        for (const i in query) {
            querystring += `${i}=${query[i]}&`;
        }

        fetch(`/came/api/solicitud?${querystring}page=${meta.page}&perPage=${meta.perPage}`)
            .then(response => { return response.json()}, error => {console.error(error)})
            .then(json => {setSolicitudes(json.data); setMeta(json.meta)})
            .finally(() => { setIsLoading(false)});

    }

    const showPaginator = () => {
        return meta.total < meta.perPage ? 'none' : 'block';
    }

    return (
        <>
            <Loader show={isLoading}/>
            <div className="col-md-3">
                <label> &#160;</label>
                <a href={'/came/solicitud/create'} id="btn_solicitud" className={'form-control btn btn-default'}>Agregar
                    Solicitud</a>
            </div>
            <div className="col-md-2"/>
            <div className="col-md-4"> </div>
            <div className="col-md-3">
                <div className={``}>
                    <label htmlFor="perpage">Tamaño de Página: </label>
                    <select id="perpage" className="form-control"
                            onChange={e => {setMeta(Object.assign(meta, {perPage: e.target.value, page: 1}));  handleSearchEvent(); }}>
                        {/*<option value="1">1</option>*/}
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                    <span className="help-block"> </span>
                </div>
            </div>
            <div className="col-md-12">
                <div className="panel panel-default">
                    <div style={{textAlign: 'center', display: (props.solicitudes.length <= 0 ? 'block': 'none'), padding:'80px 0px'}}><h3>No hay ninguna solicitud registrada</h3></div>
                    <div className={'table-responsive'} style={{display: (props.solicitudes.length > 0 ? 'block': 'none')}}>
                        <table className="table">
                            <thead>
                            <tr>
                                <th>No. de solicitud <br/><input type="text" placeholder={'No. de solicitud'}
                                           onChange={e => {setQuery(Object.assign(query,{no_solicitud: e.target.value})); handleSearchEvent()}}/></th>
                                <th>Institución Educativa <br/> <input type="text" placeholder={'Institución Educativa'}
                                            onChange={e => {setQuery(Object.assign(query,{institucion: e.target.value})); handleSearchEvent()}}/></th>
                                <th>Fecha <input type="date" placeholder={'Año-mes-día'}
                                                  onChange={e => {setQuery(Object.assign(query,{fecha: e.target.value})); handleSearchEvent()}}/></th>
                                <th>No. de campos clínicos</th>
                                <th>Estado</th>
                                <th> </th>
                            </tr>
                            </thead>
                            <tbody>
                            {solicitudes.map(solicitud => {
                                return (
                                    <tr key={solicitud.id}>
                                        <td><a href={`/came/solicitud/${solicitud.id}`}>{solicitud.noSolicitud}</a></td>
                                        <td>{solicitud.institucion.nombre}</td>
                                        <td>{solicitud.fecha}</td>
                                        <td>
                                            Solicitados: {solicitud.camposClinicosSolicitados} <br/>
                                            Autorizados: {solicitud.camposClinicosAutorizados}
                                        </td>
                                        <td>{solicitud.estatusCameFormatted}</td>
                                        <td><SolicitudAccion solicitud={solicitud}/></td>
                                    </tr>
                                )
                            })}
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div className={'col-md-6'} style={{display: (meta.total>0?'block':'none')}}>
                            <br/>
                            <p>Mostrando {(meta.page * meta.perPage) - meta.perPage + 1} {((meta.perPage * meta.page) < meta.total) ?`al ${meta.perPage * meta.page}`: `al ${meta.total}`} de {meta.total}</p>
                        </div>
                        <div style={{display: showPaginator(), textAlign: 'right'}} className={'col-md-6'}>
                            <ReactPaginate
                                previousLabel={'Anterior'}
                                nextLabel={'Siguiente'}
                                breakLabel={'...'}
                                breakClassName={'break-me'}
                                pageCount={meta.total / meta.perPage}
                                marginPagesDisplayed={2}
                                pageRangeDisplayed={parseInt(meta.perPage)}
                                onPageChange={value => {console.log(value); setMeta(Object.assign(meta, {page:value.selected + 1})); handleSearchEvent(query)}}
                                containerClassName={'pagination'}
                                subContainerClassName={'pages pagination'}
                                activeClassName={'active'}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default {CameTableExample, ExampleForm, SolicitudIndex};

document.addEventListener('DOMContentLoaded', () => {
    const indexDom = document.getElementById('solicitudes-table');
    const createDom = document.getElementById('solicitud-wrapper');
    const editDom = document.getElementById('solicitud-edit-wrapper');
    const showDom = document.getElementById('solicitud-show-wrapper');
    const validaMontosDom = document.getElementById('solicitud-valida-montos-wrapper');

    if (indexDom) {
        ReactDOM.render(
            <SolicitudIndex
                solicitudes={window.SOLICITUDES}
                meta={window.META}
            />, indexDom
        )
    }
    if (createDom) {
        ReactDOM.render(
            <SolicitudCreate
                instituciones={window.INSTITUCIONES}
                unidades={window.UNIDADES}
            />, createDom
        )
    }
    if (editDom) {
        ReactDOM.render(
            <SolicitudEdit
                solicitud={window.SOLICITUD}
                unidades={window.UNIDADES}
                instituciones={window.INSTITUCIONES}
            />, editDom
        )
    }
    if (showDom) {
        ReactDOM.render(
            <SolicitudShow
                solicitud={window.SOLICITUD}
                convenios={window.CONVENIOS}
            />, showDom
        )
    }
    if (validaMontosDom) {
        ReactDOM.render(
            <SolicitudValidaMontos
                solicitud={window.SOLICITUD}
            />, validaMontosDom);
    }
})

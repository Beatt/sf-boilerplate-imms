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
    const [query, setQuery] = React.useState('');

    const handleSearchEvent = (query) => {

        setIsLoading(true);
        fetch(`/api/solicitud?no_solicitud=${query}&page=${meta.page}&perPage=${meta.perPage}`)
            .then(response => { return response.json()}, error => {console.error(error)})
            .then(json => {setSolicitudes(json.data); setMeta(json.meta)})
            .finally(() => { setIsLoading(false)});

    }

    return (
        <>
            <Loader show={isLoading}/>
            <div className="col-md-3">
                <a href={'/solicitud/create'} id="btn_solicitud" className={'form-control btn btn-default'}>Agregar
                    Solicitud</a>
            </div>
            <div className="col-md-2"/>
            <div className="col-md-3">
                <div className={`form-group`}>
                    <select id="perpage" className="form-control"
                            onChange={e => {setMeta(Object.assign(meta, {perPage: e.target.value, page: 1}));  handleSearchEvent(query); }}>
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
            <div className="col-md-4">
                <form action="/solicitud" method={'get'}>
                    <div className="input-group">
                        <input type="text" className="form-control" placeholder="Buscar por Número de Solicitud" name="no_solicitud" onChange={e => {setQuery(e.target.value); handleSearchEvent(e.target.value)}}/>
                        <div className="input-group-btn">
                            <button className="btn btn-default" type="submit">
                                <i className="glyphicon glyphicon-search"/>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div className="col-md-12">
                <div className="panel panel-default">
                    <div className={'table-responsive'}>
                        <table className="table">
                            <thead>
                            <tr>
                                <th>No. de solicitud</th>
                                <th>Institución Educativa</th>
                                <th>No. de campos clínicos solicitados</th>
                                <th>No. de campos clínicos autorizados</th>
                                <th>Fecha Solicitud</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            {solicitudes.map(solicitud => {
                                return (
                                    <tr key={solicitud.id}>
                                        <td><a href={`/solicitud/${solicitud.id}`}>{solicitud.noSolicitud}</a></td>
                                        <td>{solicitud.institucion.nombre}</td>
                                        <td>{solicitud.camposClinicosSolicitados}</td>
                                        <td>{solicitud.camposClinicosAutorizados}</td>
                                        <td>{solicitud.fecha}</td>
                                        <td>{solicitud.estatusCameFormatted}</td>
                                        <td><SolicitudAccion solicitud={solicitud}/></td>
                                    </tr>
                                )
                            })}
                            </tbody>
                        </table>
                    </div>
                    <div style={{textAlign: "center"}}>
                        <ReactPaginate
                            previousLabel={'Anterior'}
                            nextLabel={'Siguiente'}
                            breakLabel={'...'}
                            breakClassName={'break-me'}
                            pageCount={meta.total / meta.perPage}
                            marginPagesDisplayed={2}
                            pageRangeDisplayed={meta.perPage}
                            onPageChange={value => {console.log(value); setMeta(Object.assign(meta, {page:value.selected + 1})); handleSearchEvent(query)}}
                            containerClassName={'pagination'}
                            subContainerClassName={'pages pagination'}
                            activeClassName={'active'}
                        />
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
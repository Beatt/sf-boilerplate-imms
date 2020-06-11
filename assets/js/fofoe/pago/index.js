import * as React from 'react'
import ReactDOM from 'react-dom'
import Loader from "../../components/Loader/Loader";
import ReactPaginate from "react-paginate";
import './index.scss';

const AccionFofoe = ({pago}) => {
    return (<></>);
}

const InformacionPago = ({pago}) => {
    return (<></>);
}

const PagoIndex = (props) => {
    const [pagos, setPagos] = React.useState(props.pagos ? props.pagos : [])
    const [isLoading, setIsLoading] = React.useState(false)
    const [meta, setMeta] = React.useState(props.meta);
    const [query, setQuery] = React.useState({});

    const handleSearchEvent = () => {
        setIsLoading(true);
        let querystring = '';
        for (const i in query) {
            querystring += `${i}=${query[i]}&`;
        }

        fetch(`/fofoe/api/pago?${querystring}page=${meta.page}&perPage=${meta.perPage}`)
            .then(response => { return response.json()}, error => {console.error(error)})
            .then(json => {setPagos(json.data); setMeta(json.meta)})
            .finally(() => { setIsLoading(false)});

    }

    const showPaginator = () => {
        return meta.total < meta.perPage ? 'none' : 'block';
    }

    return (
        <>
            <Loader show={isLoading}/>
            <div className="col-md-3">

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
                    <div style={{textAlign: 'center', display: (props.pagos.length <= 0 ? 'none': 'none'), padding:'80px 0px'}}><h3>No hay ningún comprobante de pago registrado</h3></div>
                    <div className={'table-responsive'} style={{display: (props.pagos.length > 0 ? 'block': 'block')}}>
                        <table className="table table-fofoe">
                            <thead>
                            <tr>
                                <th>
                                    Delegación <br/>
                                </th>
                                <th>
                                    Origen de los Recursos
                                </th>
                                <th>
                                    Institución Educativa
                                </th>
                                <th>
                                    Fecha Registro de Pago
                                </th>
                                <th>
                                    Información del pago
                                </th>
                                <th>
                                    Estado
                                </th>
                                <th>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            {pagos.map(pago => {
                                return (
                                    <tr key={pago.id}>
                                        <td>{pago.solicitud.delegacion.nombre}</td>
                                        <td>CCS</td>
                                        <td>{pago.solicitud.institucion.nombre}</td>
                                        <td>{pago.fechaPagoFormatted}</td>
                                        <td>
                                            <InformacionPago pago={pago}/>
                                        </td>
                                        <td>{pago.estatusFofoeFormatted}</td>
                                        <td>
                                            <AccionFofoe pago={pago}/>
                                        </td>
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

document.addEventListener('DOMContentLoaded', () => {
    const indexDom = document.getElementById('fofoe-wrapper-index');
    if (indexDom) {
        ReactDOM.render(
            <PagoIndex
                pagos={window.PAGOS}
                meta={window.META}
            />, indexDom
        )
    }
})
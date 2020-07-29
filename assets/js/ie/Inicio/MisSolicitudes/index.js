import * as React from 'react'
import ReactPaginate from 'react-paginate';
import { solicitudesGet } from "../../api/solicitud";
import { TIPO_PAGO, SOLICITUD } from "../../../constants";
import {
  getActionNameByInstitucionEducativa,
  isActionDisabledByInstitucionEducativa
} from "../../../utils";
const DEFAULT_PAGE = 1;
const DEFAULT_STRING_VALUE = '';

const PER_PAGE_DEFAULT_SELECT_VALUES = [];
PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION = 2;
PER_PAGE_DEFAULT_SELECT_VALUES.SECOND_OPTION = 10;
PER_PAGE_DEFAULT_SELECT_VALUES.THIRD_OPTION = 15;

const FILTERS_FOR_ORDERING = [];
FILTERS_FOR_ORDERING.NO_SOLICITUD_MENOR_A_MAYOR = 'order_by_no_solicitud_menor_a_mayor';
FILTERS_FOR_ORDERING.NO_SOLICITUD_MAYOR_A_MENOR = 'order_by_no_solicitud_mayor_a_menor';
FILTERS_FOR_ORDERING.FECHA_DE_SOLICITUD_MAS_RECIENTE = 'order_by_fecha_de_solicitud_mas_reciente';
FILTERS_FOR_ORDERING.FECHA_DE_SOLICITUD_MAS_ANTIGUA = 'order_by_fecha_de_solicitud_mas_antigua';

const MisSolicitudes = () => {
  const { useState, useEffect } = React
  const [ camposClinicos, setCamposClinicos ] = useState([])
  const [ search, setSearch ] = useState(DEFAULT_STRING_VALUE)
  const [ tipoPago, setTipoPago ] = useState(DEFAULT_STRING_VALUE)
  const [ estatus, setEstatus ] = useState(DEFAULT_STRING_VALUE)
  const [ orderBy, setOrderBy ] = useState(DEFAULT_STRING_VALUE)
  const [ currentPage, setCurrentPage ] = useState(DEFAULT_PAGE)
  const [ perPage, setPerPage ] = useState(PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION)
  const [ isLoading, toggleLoading ] = useState(false)
  const [ pagination, setPagination ] = useState({
    pageCount: 0,
    totalCount: 0,
    firstItemNumber: 0,
    lastItemNumber: 0
  })

  function isRequestAllowed() {
    return currentPage !== null ||
      tipoPago !== DEFAULT_STRING_VALUE ||
      search !== DEFAULT_STRING_VALUE ||
      PER_PAGE_DEFAULT_SELECT_VALUES.includes(perPage) ||
      orderBy !== DEFAULT_STRING_VALUE ||
      estatus !== DEFAULT_STRING_VALUE
  }

  useEffect(() => {
    if(isRequestAllowed()) getCamposClinicos();
  }, [currentPage, tipoPago, search, perPage, orderBy, estatus])

  function handleSearch() {
    if(!search) return;
    getCamposClinicos();
  }

  function getCamposClinicos() {
    toggleLoading(true);

    solicitudesGet(
      tipoPago,
      estatus,
      currentPage,
      perPage,
      orderBy,
      search
    ).then((res) => {
        setCamposClinicos(res.camposClinicos)
        setPagination({
          pageCount: res.paginationData.pageCount,
          totalCount: res.paginationData.totalCount,
          firstItemNumber: res.paginationData.firstItemNumber,
          lastItemNumber: res.paginationData.lastItemNumber
        })
      })
      .finally(() => toggleLoading(false))
  }

  function handleStatusAction(solicitud) {
    if(isActionDisabledByInstitucionEducativa(solicitud.estatus)) return;

    let redirectRoute = ''

    switch(solicitud.estatus) {
      case SOLICITUD.CONFIRMADA:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/registrar-montos`
        break
      case SOLICITUD.MONTOS_INCORRECTOS_CAME:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/corregir-montos`
        break
      case SOLICITUD.MONTOS_VALIDADOS_CAME:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/seleccionar-forma-de-pago`
        break
      case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/detalle-de-forma-de-pago`
        break
      case SOLICITUD.CARGANDO_COMPROBANTES:
        if(TIPO_PAGO.MULTIPLE === solicitud.tipoPago) redirectRoute = `/ie/solicitudes/${solicitud.id}/detalle-de-solicitud-multiple`
        else redirectRoute = `/ie/pagos/${solicitud.ultimoPago}/carga-de-comprobante-de-pago`
    }

    window.location.href = redirectRoute
  }

  function cleanFilters() {
    setTipoPago(DEFAULT_STRING_VALUE)
    setSearch(DEFAULT_STRING_VALUE)
    setEstatus(DEFAULT_STRING_VALUE)
    setCurrentPage(DEFAULT_PAGE)
    setPerPage(parseInt(PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION))
  }

  function handleNoSolicitud(event, solicitud) {
    event.preventDefault();

    window.location = solicitud.tipoPago === TIPO_PAGO.MULTIPLE ?
      `/ie/solicitudes/${solicitud.id}/detalle-de-solicitud-multiple` :
      `/ie/solicitudes/${solicitud.id}/detalle-de-solicitud`
  }

  return(
    <div className='row'>
      <div className="col-md-2">
        <div className="form-group">
          <label htmlFor="solicitud_tipoPago">Tipo de pago</label>
          <select
            id="solicitud_tipoPago"
            className='form-control'
            onChange={({ target }) => setTipoPago(target.value)}
            value={tipoPago}
          >
            <option value=''>Ver todos</option>
            <option value={TIPO_PAGO.UNICO}>Pago único</option>
            <option value={TIPO_PAGO.MULTIPLE}>Pago multiple</option>
          </select>
        </div>
      </div>
      <div className="col-md-3">
        <div className="form-group">
          <label htmlFor="solicitud_estado">Estado de la solicitud</label>
          <select
            id="solicitud_estado"
            className='form-control'
            onChange={({ target }) => setEstatus(target.value)}
            value={estatus}
          >
            <option value=''>Ver todos</option>
            {
              Object.values(SOLICITUD).map(item => {
                if(item === SOLICITUD.CREADA) return;
                return <option value={item}>{item}</option>;
              })
            }
          </select>
        </div>
      </div>
      <div className="col-md-3">
        <div className="form-group">
          <label htmlFor="solicitud_tipoPago">Ordenar por</label>
          <select
            id="solicitud_tipoPago"
            className='form-control'
            onChange={({ target }) => setOrderBy(target.value)}
            value={orderBy}
          >
            <option value=''>Ver todos</option>
            <option value={FILTERS_FOR_ORDERING.NO_SOLICITUD_MAYOR_A_MENOR}>No. de solicitud. de mayor a menor</option>
            <option value={FILTERS_FOR_ORDERING.NO_SOLICITUD_MENOR_A_MAYOR}>No. de solicitud. de menor a mayor</option>
            <option value={FILTERS_FOR_ORDERING.FECHA_DE_SOLICITUD_MAS_RECIENTE}>Fecha de solicitud. más reciente</option>
            <option value={FILTERS_FOR_ORDERING.FECHA_DE_SOLICITUD_MAS_ANTIGUA}>Fecha de solicitud. más antigua</option>
          </select>
        </div>
      </div>
      <div className='col-md-4 mt-20 text-right'>
        <div className='navbar-form navbar-right'>
          <div className="form-group">
            <input
              type="text"
              placeholder='Buscar por No. de solicitud o fecha...'
              className='input-sm form-control'
              onChange={({ target }) => setSearch(target.value)}
              style={{ width: 250 }}
              value={search}
            />
          </div>
          <button
            type="button"
            className="btn btn-default"
            onClick={handleSearch}
          >
            Buscar
          </button>
        </div>
      </div>
      <div className="col-md-12 mb-20">
        <div className="row">
          <div className="col-md-3">
            <button
              className="btn btn-default btn-block"
              onClick={cleanFilters}
            >
              Limpiar filtros
            </button>
          </div>
        </div>
      </div>
      <div className="col-md-12">
        <div className="row">
          <div className="col-md-10">
            <ReactPaginate
              previousLabel={'Anterior'}
              nextLabel={'Siguiente'}
              breakLabel={'...'}
              breakClassName={'break-me'}
              pageCount={pagination.pageCount}
              marginPagesDisplayed={5}
              pageRangeDisplayed={2}
              initialPage={currentPage - 1}
              forcePage={currentPage - 1}
              onPageChange={(currentPage) => {
                setCurrentPage(currentPage.selected + 1)
              }}
              containerClassName={'pagination'}
              subContainerClassName={'pages pagination'}
              activeClassName={'active'}
            />
          </div>
          <div className="col-md-2">
            <div className="form-group">
              <select
                name=""
                id=""
                className='form-control'
                onChange={({ target }) => {
                  setCurrentPage(DEFAULT_PAGE)
                  setPerPage(parseInt(target.value))
                }}
                value={perPage}
              >
                <option value={PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION}>
                  Mostrar {PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION}
                </option>
                <option value={PER_PAGE_DEFAULT_SELECT_VALUES.SECOND_OPTION}>
                  Mostrar {PER_PAGE_DEFAULT_SELECT_VALUES.SECOND_OPTION}
                </option>
                <option value={PER_PAGE_DEFAULT_SELECT_VALUES.THIRD_OPTION}>
                  Mostrar {PER_PAGE_DEFAULT_SELECT_VALUES.THIRD_OPTION}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div className="col-md-12">
        <div className="panel panel-default">
          <div className="panel-body">
            <table className='table'>
              <thead className='headers'>
              <tr>
                <th>No. de solicitud</th>
                <th>No. de campos clínicos <br/>solicitados</th>
                <th>No. de campos clínicos <br/>autorizados</th>
                <th>Fecha de solicitud</th>
                <th>Tipo de pago</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
              </thead>
              <tbody>
              {
                isLoading ?
                  <tr>
                    <th className='text-center' colSpan={7}>Cargando información...</th>
                  </tr> :
                  camposClinicos.length !== 0 ?
                    camposClinicos.map((solicitud, index) => (
                      <tr key={index}>
                        <th>
                          <a
                            href="#"
                            onClick={event => handleNoSolicitud(event, solicitud)}
                          >{solicitud.noSolicitud}</a>
                        </th>
                        <th>{solicitud.noCamposSolicitados}</th>
                        <th>{solicitud.noCamposAutorizados}</th>
                        <th>{solicitud.fecha}</th>
                        <th>{solicitud.tipoPago}</th>
                        <th>{solicitud.estatus}</th>
                        <th>
                          <button
                            className='btn btn-default'
                            disabled={isActionDisabledByInstitucionEducativa(solicitud.estatus)}
                            onClick={() => handleStatusAction(solicitud)}
                          >
                            {getActionNameByInstitucionEducativa(solicitud.estatus, solicitud.tipoPago)}
                          </button>
                        </th>
                      </tr>
                    )) :
                    <tr>
                      <th className='text-center' colSpan={7}>No hay registros disponibles</th>
                    </tr>
              }
              </tbody>
            </table>
          </div>
          <p className='text-center'>Mostrando {pagination.firstItemNumber}-{pagination.lastItemNumber} de {pagination.totalCount}</p>
        </div>
      </div>
    </div>
  )
}

export default MisSolicitudes

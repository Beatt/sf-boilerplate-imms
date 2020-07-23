import * as React from 'react'
import ReactPaginate from 'react-paginate';
import { solicitudesGet } from "../../api/solicitud";
import { TIPO_PAGO, SOLICITUD } from "../../../constants";
import {
  getActionNameByInstitucionEducativa,
  isActionDisabledByInstitucionEducativa
} from "../../../utils";
import GestionPagoModal from "../../components/GestionPagoModal";
const DEFAULT_PAGE = 1;
const DEFAULT_STRING_VALUE = '';
const PER_PAGE_DEFAULT_SELECT_VALUES = [];
PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION = 2;
PER_PAGE_DEFAULT_SELECT_VALUES.SECOND_OPTION = 3;
PER_PAGE_DEFAULT_SELECT_VALUES.THIRD_OPTION = 5;

const MisSolicitudes = ({ totalInit, paginatorTotalPerPage }) => {
  const { useState, useEffect } = React
  const [ camposClinicos, setCamposClinicos ] = useState([])
  const [ search, setSearch ] = useState(DEFAULT_STRING_VALUE)
  const [ tipoPago, setTipoPago ] = useState(DEFAULT_STRING_VALUE)
  const [ total, setTotal ] = useState(totalInit)
  const [ currentPage, setCurrentPage ] = useState(DEFAULT_PAGE)
  const [ perPage, setPerPage ] = useState(PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION)
  const [ isLoading, toggleLoading ] = useState(false)
  const [ modalIsOpen, setModalIsOpen ] = React.useState(false);
  const [ campoClinicoSelected, setCampoClinicoSelected ] = useState({
    pago: { id: null }
  })

  function isRequestAllowed() {
    return currentPage !== null ||
      tipoPago !== DEFAULT_STRING_VALUE ||
      search !== DEFAULT_STRING_VALUE ||
      PER_PAGE_DEFAULT_SELECT_VALUES.includes(perPage)
  }

  useEffect(() => {
    if(isRequestAllowed()) getCamposClinicos();
  }, [currentPage, tipoPago, search, perPage])

  function handleSearch() {
    if(!search) return;
    getCamposClinicos();
  }

  function getCamposClinicos() {
    toggleLoading(true);

    solicitudesGet(
      tipoPago,
      currentPage,
      perPage,
      search
    ).then((res) => {
        setCamposClinicos(res.camposClinicos)
        setTotal(res.total)
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
        else {
          setModalIsOpen(true)
          setCampoClinicoSelected({
            pago: { id: solicitud.ultimoPago }
          })
          return
        }
    }

    window.location.href = redirectRoute
  }

  function closeModal() {
    setModalIsOpen(false)
  }

  function isPaginateEnabledToShow() {
    return total >= paginatorTotalPerPage;
  }

  function cleanFilters() {
    setTipoPago(DEFAULT_STRING_VALUE)
    setSearch(DEFAULT_STRING_VALUE)
    setCurrentPage(DEFAULT_PAGE)
  }

  function handleNoSolicitud(event, solicitud) {
    event.preventDefault();

    window.location = solicitud.tipoPago === TIPO_PAGO.MULTIPLE ?
      `/ie/solicitudes/${solicitud.id}/detalle-de-solicitud-multiple` :
      `/ie/solicitudes/${solicitud.id}/detalle-de-solicitud`
  }

  function getTotalByPage() {

    let first = (PAGINATOR_TOTAL_PER_PAGE_PROPS - currentPage);
    let second = first * PAGINATOR_TOTAL_PER_PAGE_PROPS;

    return <>{first}-{second}</>;
  }

  return(
    <div className='row'>
      <div className="col-md-3">
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
      <div className="col-md-3"/>
      <div className='col-md-6 mb-10 text-right'>
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
          {
            modalIsOpen &&
            <GestionPagoModal
              modalIsOpen={modalIsOpen}
              closeModal={closeModal}
              pagoId={campoClinicoSelected.pago.id}
            />
          }
        </div>
      </div>
      <div className="col-md-12">
        {
          isPaginateEnabledToShow() &&
          <div className="row">
            <div className="col-md-10">
              <ReactPaginate
                previousLabel={'Anterior'}
                nextLabel={'Siguiente'}
                breakLabel={'...'}
                breakClassName={'break-me'}
                pageCount={total}
                marginPagesDisplayed={5}
                pageRangeDisplayed={2}
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
                <label htmlFor="">Mostrar</label>
                <select
                  name=""
                  id=""
                  className='form-control'
                  onChange={({ target }) => setPerPage(parseInt(target.value))}
                >
                  <option value={PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION}>
                    {PER_PAGE_DEFAULT_SELECT_VALUES.FIRST_OPTION}
                  </option>
                  <option value={PER_PAGE_DEFAULT_SELECT_VALUES.SECOND_OPTION}>
                    {PER_PAGE_DEFAULT_SELECT_VALUES.SECOND_OPTION}
                  </option>
                  <option value={PER_PAGE_DEFAULT_SELECT_VALUES.THIRD_OPTION}>
                    {PER_PAGE_DEFAULT_SELECT_VALUES.THIRD_OPTION}
                  </option>
                </select>
              </div>
            </div>
          </div>
        }
      </div>
    </div>
  )
}

export default MisSolicitudes

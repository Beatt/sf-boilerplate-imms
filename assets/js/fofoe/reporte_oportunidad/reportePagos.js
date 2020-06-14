import {clickAction} from "../../pregrado/utils";

const getReportePagos = (desde, hasta,
  search, page, limit) => {
  let urlQuery = `/fofoe/reporte_oportunidad_pago?desde=${desde}`
    + `&hasta=${hasta}&search=${search}`
    + `&page=${page}&limit=${limit}`;

  return fetch(urlQuery)
    .then(function(response) {
      return response.json();
    })
}

const getReportePagosCSV = (desde, hasta, search) => {

  let urlQuery = `/fofoe/reporte_oportunidad_pago?desde=${desde}`
    + `&hasta=${hasta}&search=${search}`
    + `&export=1`;

  return clickAction(urlQuery);
}

export {
  getReportePagos, getReportePagosCSV
}
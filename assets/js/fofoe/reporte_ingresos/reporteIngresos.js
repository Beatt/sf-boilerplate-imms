const getReporteIngresos = (anio) => {
  return fetch(`/fofoe/reporte_ingresos?anio=${anio}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  getReporteIngresos
}
const getCamposClinicos = ( tipoCASel, delegacionSel, carreraSel, estadoSolSel,
                            search, currentPage) => {
  return fetch(`/pregrado/reporte?offset=${currentPage}&search=${search}`
    + `&cicloAcademico=${tipoCASel}&estatus=${estadoSolSel}`
    + `&delegacion=${delegacionSel}&carrera=${carreraSel}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  getCamposClinicos
}

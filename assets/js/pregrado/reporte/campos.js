const getCamposClinicos = ( tipoCASel, delegacionSel, carreraSel, estadoSolSel,
                            search, page, limit) => {
  return fetch(`/pregrado/reporte?page=${page}&limit=${limit}&search=${search}`
    + `&cicloAcademico=${tipoCASel}&estatus=${estadoSolSel}`
    + `&delegacion=${delegacionSel}&carrera=${carreraSel}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  getCamposClinicos
}

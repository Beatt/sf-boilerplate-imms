const getCamposClinicos = ( tipoCASel, delegacionSel, carreraSel, estadoSolSel,
                            search, page, limit) => {
  return fetch(`/pregrado/reporte?page=${page}&limit=${limit}&search=${search}`
    + `&cicloAcademico=${tipoCASel}&estatus=${estadoSolSel}`
    + `&delegacion=${delegacionSel}&carrera=${carreraSel}`)
    .then(function(response) {
      return response.json();
    })
}

const getCamposClinicosCSV = ( tipoCASel, delegacionSel, carreraSel, estadoSolSel,
                            search) => {
  return fetch(`/pregrado/reporte?search=${search}`
    + `&cicloAcademico=${tipoCASel}&estatus=${estadoSolSel}`
    + `&delegacion=${delegacionSel}&carrera=${carreraSel}`
    + `&export=1`).then(function(response) {
      downloadAction('reportePregrado.csv', response);
    });
}

const downloadAction = (filename, data) => {
  var pom = document.createElement('a');
  pom.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(data));
  pom.setAttribute('download', filename);

  if (document.createEvent) {
    var event = document.createEvent('MouseEvents');
    event.initEvent('click', true, true);
    pom.dispatchEvent(event);
  }
  else {
    pom.click();
  }
}

export {
  getCamposClinicos, getCamposClinicosCSV
}

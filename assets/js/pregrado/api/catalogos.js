const getCarreras = () => {
  return fetch('/api/pregrado/carrera')
    .then(function (response) {
      return response.json();
    }).then(function (json) {
      json.data.forEach(function (obj) {
        obj['nombre'] = obj.nivelAcademico.nombre.concat(" - ", obj.nombre);
      });
      return json;
    });
}

const getCiclosAcademicos = () => {
  return fetch('/api/pregrado/ciclo_academico')
    .then(function (response) {
      return response.json()
    })
}

const getDelegaciones = () => {
  return fetch('/api/pregrado/delegacion')
    .then(function (response) {
      return response.json()
    })
}

const getEstatusCampoClinico = () => {
  return fetch('/estatus-campos-clinicos')
    .then(function (response) {
      return response.json();
    })
}

export {
  getCarreras, getCiclosAcademicos, getDelegaciones, getEstatusCampoClinico
}

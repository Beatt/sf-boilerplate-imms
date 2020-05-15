const getEstatusCampoClinico = () => {
  return fetch('/estatus-campos-clinicos')
    .then(function(response) {
      return response.json();
    })
}

export {
  getEstatusCampoClinico
}

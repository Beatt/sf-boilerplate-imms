const solicitudesGet = (tipoPago, currentPage, search) => {
  return fetch(`/ie/inicio?offset=${currentPage}&search=${search}&tipoPago=${tipoPago}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

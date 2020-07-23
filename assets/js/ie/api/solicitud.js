const solicitudesGet = (tipoPago, currentPage, perPage, search) => {
  return fetch(`/ie/inicio?offset=${currentPage}&perPage=${perPage}&search=${search}&tipoPago=${tipoPago}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

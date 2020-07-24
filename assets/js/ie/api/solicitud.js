const solicitudesGet = (tipoPago, currentPage, perPage, orderBy, search) => {
  return fetch(`/ie/inicio?offset=${currentPage}&perPage=${perPage}&orderBy=${orderBy}&search=${search}&tipoPago=${tipoPago}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

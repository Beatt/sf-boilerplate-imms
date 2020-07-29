const solicitudesGet = (tipoPago, estatus, currentPage, perPage, orderBy, search) => {
  return fetch(`/ie/inicio?offset=${currentPage}&perPage=${perPage}&orderBy=${orderBy}&search=${search}&tipoPago=${tipoPago}&estatus=${estatus}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

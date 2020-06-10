const solicitudesGet = (institucionId, tipoPago, currentPage, search) => {
  return fetch(`/instituciones/${institucionId}/solicitudes?offset=${currentPage}&search=${search}&tipoPago=${tipoPago}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

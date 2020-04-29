const solicitudesGet = (institucionId, tipoPago, estatusSelected, currentPage, search) => {
  return fetch(`/instituciones/${institucionId}/solicitudes?offset=${currentPage}&search=${search}&tipoPago=${tipoPago}&estatus=${estatusSelected}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

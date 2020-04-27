const solicitudesGet = (institucionId, currentPage, search) => {
  return fetch(`/instituciones/${institucionId}/solicitudes?offset=${currentPage}&search=${search}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

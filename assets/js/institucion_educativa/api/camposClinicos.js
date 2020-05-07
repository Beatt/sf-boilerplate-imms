const solicitudesGet = (institucionId, solicitudId, search) => {
  return fetch(`/instituciones/${institucionId}/solicitudes/${solicitudId}?search=${search}`)
    .then(function (response) {
      return response.json();
    })
}

export {
  solicitudesGet
}

const solicitudesGet = () => {
    //return fetch(`/instituciones/${institucionId}/solicitudes?offset=${currentPage}&search=${search}&tipoPago=${tipoPago}&estatus=${estatusSelected}`)
    return fetch('/solicitud.index')  
    .then(function(response) {
        return response.json();
      })
  }
  
  export {
    solicitudesGet
  }
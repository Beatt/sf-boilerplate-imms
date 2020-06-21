const getGestionPagoAsync = (id) => {
  return fetch(`/ie/pagos/gestion-de-pago/${id}`)
    .then(function(response) {
      return response.json();
    })
}

export {
  getGestionPagoAsync
}

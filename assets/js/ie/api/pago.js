const getGestionPago = () => {
  return fetch('/por-definir')
    .then(function(response) {
      return response.json();
    })
}

export {
  getGestionPago
}

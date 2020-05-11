const solicitudesGet = (institucionId, solicitudId, search) => {
  return fetch(`/instituciones/${institucionId}/solicitudes/${solicitudId}?search=${search}`)
    .then(function (response) {
      return response.json();
    })
}

const uploadComprobantePago = (id, file) => {
  const form = new FormData();
  form.append('comprobantePago[campoClinico]', id);
  form.append('comprobantePago[file]', file[0]);

  return fetch('/campos-clinicos:uploadComprobantePago', {
    method: 'POST',
    body: form
  }).then((res) => res.json())
}

export {
  solicitudesGet,
  uploadComprobantePago
}

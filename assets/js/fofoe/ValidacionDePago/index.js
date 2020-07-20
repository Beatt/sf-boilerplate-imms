import * as React from 'react';
import ReactDOM from 'react-dom';

const ValidacionDePago = () => {

  return(
    <h2>Componente de validación de pago</h2>
  )
}

export default ValidacionDePago;

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ValidacionDePago/>,
    document.getElementById('validacion-de-pago-component')
  )
});

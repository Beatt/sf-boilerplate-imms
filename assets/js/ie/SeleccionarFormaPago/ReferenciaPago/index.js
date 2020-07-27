import * as React from 'react'
import { TIPO_PAGO } from "../../../constants";
import Swal from 'sweetalert2'
import 'sweetalert2/src/sweetalert2.scss'

const ReferenciaPago = (
  {
    action,
    camposClinicos
  }) => {

  const { useRef } = React
  const formRef = useRef(null)

  function getTotalCamposClinicos() {
    return camposClinicos.length;
  }

  function haveMoreThanOneAuthorizedCampoClinico() {
    return getTotalCamposClinicos() !== 1;
  }

  function handleSubmit(event) {
    event.preventDefault();

    const tipoPago = formRef.current.elements['forma_pago[tipoPago]'].value;
    if (!tipoPago) return;

    let title = '';
    if(tipoPago === TIPO_PAGO.UNICO) {
      title = haveMoreThanOneAuthorizedCampoClinico() ?
        `Se generará una única referencia por el monto total de los ${getTotalCamposClinicos()} campos clínicos autorizados.` :
        'Se generará una única referencia por el monto total del campos clínico autorizado.';
    }else if (tipoPago === TIPO_PAGO.MULTIPLE) {
      title = haveMoreThanOneAuthorizedCampoClinico() ?
        `Deberá pagar el monto total de cada campo clínico de manera independiente. En total se le generarán ${getTotalCamposClinicos()} referencias de pago diferentes.` :
        `Se generará una única referencia por el monto total del campos clínico autorizado.`;
    }else {
      console.error('El tipo de pago seleccionado no existe.');
    }

    Swal.fire({
      title: title,
      text: "Tenga en cuenta que esta opción es inamovible durante el resto del proceso y que será la forma en la que se realizará la facturación de su pago.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '¡Si, estoy seguro!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {
        formRef.current.submit()
      }
    })

  }

  return (
    <form
      action={action}
      method={'post'}
      onSubmit={handleSubmit}
      ref={formRef}
    >
      <div className='row'>
        <div className="col-md-5">
          <div className="radio">
            <label htmlFor="forma_pago_tipoPago_unico">
              <input
                id='forma_pago_tipoPago_unico'
                type="radio"
                name='forma_pago[tipoPago]'
                value={TIPO_PAGO.UNICO}
              />Generar un único formato para pagar todos los campos clínicos autorizados en esta solicitud.
            </label>
          </div>
          <p className='text-danger'>Considere que el pago se realizará de manera individual por cada uno de los campos
            clínicos solicitados.</p>
        </div>
        <div className="col-md-2"/>
        <div className="col-md-5">
          <div className="radio">
            <label htmlFor="forma_pago_tipoPago_multiple">
              <input
                id='forma_pago_tipoPago_multiple'
                type="radio"
                name='forma_pago[tipoPago]'
                value={TIPO_PAGO.MULTIPLE}
              />Generar un formato para pagar de manera individual cada uno de los campos clínicos autorizados en esta
              solicitud.
            </label>
          </div>
          <p className='text-danger'>Considere que el pago deberá realizarce en una sola exhibición y por el monto total
            de la solicitud.</p>
        </div>
        <div className="col-md-12 mt-20">
          <p><strong>NOTA: Seleccione la opción que más le convenga, ya que una vez seleccionada la modalidad de pago, esta es inamovible durante el resto del procedimiento.</strong></p>
        </div>
        <div className="col-md-12 mt-20">
          <div className="row">
            <div className="col-md-9"/>
            <div className="col-md-3">
              <button
                type="submit"
                className='btn btn-success btn-block'
              >
                Guardar
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  )
}

export default ReferenciaPago

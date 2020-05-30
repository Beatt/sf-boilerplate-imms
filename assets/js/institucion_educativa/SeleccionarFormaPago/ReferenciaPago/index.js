import * as React from 'react'
import { TIPO_PAGO } from "../../../constants";
import Swal from 'sweetalert2'
import 'sweetalert2/src/sweetalert2.scss'

const ReferenciaPago = ({ action }) => {

  const { useRef } = React
  const formRef = useRef(null)

  function handleSubmit(event) {
    event.preventDefault()

    Swal.fire({
      title: '¿Estás seguro que deseas pagar por campos clinicos?',
      text: "Recuerda que esta opción es inamovible durante el resto del proceso",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '¡Si, estoy seguro!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {
        Swal.fire(
          'Deleted!',
          'Your file has been deleted.',
          'success'
        )
        //formRef.current.submit()
      }
    })

  }

  return(
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
              />Generar un único Formato para pagar todos los Campos Clínicos Autorizados en esta solicitud.
            </label>
          </div>
          <p className='text-danger'>Considere que el pago se realizará de manera individual por cada uno de los campos clínicos solicitados.</p>
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
              />Generar un único Formato para pagar todos los Campos Clínicos Autorizados en esta solicitud.
            </label>
          </div>
          <p className='text-danger'>Considere que el pago deberá realizarce en una sola exibición y por el monto total de la solicitud.</p>
        </div>
        <div className="col-md-12 mt-20">
          <p><strong>NOTA: Seleccione la opción que mas le convenga, ya que una vez seleccionada la forma de pago, esta es inamovible durante el resto del procedimiento.</strong></p>
        </div>
        <div className="col-md-12 mt-20">
          <div className="row">
            <div className="col-md-10"/>
            <div className="col-md-2">
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

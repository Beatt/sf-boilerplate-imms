import React, {Fragment} from 'react'
import {moneyFormat} from "../../../utils";

const DetalleMontoPago = ({campoClinico}) => {
  const monto = campoClinico.montoCarrera;

  console.log(campoClinico);

  function calcularSubtotalCAI(descIns, descCol) {
    return descIns > 0 || descCol > 0  ?

      monto.montoInscripcion
      *((100-descIns)/100.0)
      + monto.montoColegiatura
      *((100-descCol)/100.0)

      : monto.montoInscripcion + monto.montoColegiatura;
  }

  function calcularSubtotal(numAlumnos, descIns, descCol) {
    let subtotal1 = calcularSubtotalCAI(descIns, descCol);
    console.log(subtotal1);
    let subtotal2 = subtotal1*(campoClinico.convenio.cicloAcademico.id === 1 ? 0.005 : .50);
    console.log(subtotal2);
    let numSemanas = (campoClinico.convenio.cicloAcademico.id === 1 ? campoClinico.weeks : 1);
    console.log(numSemanas);
    return numAlumnos*subtotal2*numSemanas;
  }

  let numAlumnosSinDesc = Math.max(0,
      campoClinico.lugaresAutorizados - monto.descuentos.reduce((acc, elem) => acc+elem.numAlumnos , 0));

  return (
    <div>
      Detalle: <br />
      {
        monto.descuentos.map((elem, idx) =>
            <div key={idx} className={'col-md-12'}>
              <div className={'col-md-6'}>
              Num Alumnos: {elem.numAlumnos},
              {elem.descuentoInscripcion > 0 ?
                <Fragment>Descuento Inscripción: {elem.descuentoInscripcion}%, </Fragment>
                : null
              }
              {elem.descuentoColegiatura > 0 ?
                <Fragment>Descuento Colegiatura: {elem.descuentoColegiatura}%, </Fragment>
                : null
              }
              </div>
              <div className={'col-md-3'}>Importe por Alumno: {moneyFormat(calcularSubtotal(1, elem.descuentoInscripcion, elem.descuentoColegiatura))}</div>
              <div className={'col-md-3'}>Subtotal Campo Clínico: {moneyFormat(calcularSubtotal(elem.numAlumnos, elem.descuentoInscripcion, elem.descuentoColegiatura))}</div>
            </div>
          )
      }
      {
        numAlumnosSinDesc > 0 ?
          <div className={'col-md-12'}>
            <div className={'col-md-6'}>Num Alumnos: {numAlumnosSinDesc},</div>
            <div className={'col-md-3'}>Importe por Alumno: {moneyFormat(calcularSubtotal(1, 0, 0))}</div>
            <div className={'col-md-3'}>Subtotal Campo Clínico: {moneyFormat(calcularSubtotal(numAlumnosSinDesc, 0, 0))}</div>
          </div>
          : null
      }
    </div>
  );
}

export default DetalleMontoPago;
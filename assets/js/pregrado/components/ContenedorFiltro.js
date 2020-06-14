import * as React from "react";
import {Fragment} from "react";

const ContenedorFiltro = ({
  EtiquetaFiltro, name, valores, setValSel, tipo
}) => {

  function handler(e) {
    setValSel(e.value !== '' ? e.value : null)
  }

  return (
    <div className="col-md-3">
      <div className="form-group">
        <label htmlFor={name}>{EtiquetaFiltro}</label>
        {tipo === "Select" ?
            <select
              name={name}
              id={"id" + name}
              className='form-control'
              onChange={({target}) => handler(target)}
            >
              <option value="">Elige una opción</option>
              { valores.map((valor) =>
                <option  value={valor.id}  key={valor.id}>
                  {valor.nombre}
                </option>
              )}
            </select>
          : tipo === "date" ?
              <input className='form-control' type='date'
                     name={name}
                     id={"id" + name}
                     onChange={({target}) => handler(target)} />
            : ''
        }
      </div>
    </div>
  );
}

export default ContenedorFiltro
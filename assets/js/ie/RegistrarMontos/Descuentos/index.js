import * as React from 'react'

const RegistrarDescuentos = (props) => {
  const [inputList, setInputList] = React.useState(
    props.descuentos ?
      props.descuentos.map((desc, i) => {
        return {
          numAlumnos: desc.numAlumnos,
          descuentoInscripcion: desc.descuentoInscripcion,
          descuentoColegiatura: desc.descuentoColegiatura
        }
      })
      : []);
  const [errorDesc, setErrorDesc] = React.useState(false);

  const maxLugaresAutorizados = props.campos ? props.campos.reduce(
    (acc, elem) => {
      if (elem.carrera.id === props.carrera.id) {
        acc = Math.max(acc, elem.lugaresAutorizados);
      }
      return acc;
    }, 0 ) : 0;

  const validate = () => {
    const totalAlumDesc = inputList.reduce((acc, elem) => {return acc + parseInt(elem.numAlumnos) }, 0);
    setErrorDesc(totalAlumDesc > maxLugaresAutorizados);
    return totalAlumDesc <= maxLugaresAutorizados;
  }

  // handle click event of the Remove button
  const handleRemoveClick = index => {
    const list = [...inputList];
    list.splice(index, 1);
    setInputList(list);
  };

  // handle click event of the Add button
  const handleAddClick = (e) => {
    e.preventDefault();
    setInputList([...inputList,
      { numAlumnos: "",
        descuentoInscripcion: "",
        descuentoColegiatura: "" }]);
  };

  const handleInputChange = (e, index, name) => {
    const { nameF, value } = e.target;
    const list = [...inputList];
    list[index][name] = value;
    setInputList(list);
    if (props.indexMonto >= 0) {
      props.onChange(props.indexMonto, list, validate());
    }
  };

  return (
    <div className={'form-inline'}>
      {inputList.map((x, i) => {
        const prefixName = `${props.prefixName}[${i}]`
        const descId = `${props.carrera.id}-${i}`;
        return (
          <div className="row mb-5" key={i}>
              <div className={'form-group col-md-3'}>
                { i===0 ?
                  <label htmlFor="numAlumnos">Número de Alumnos</label>
                  : null
                }
                <input
                className={'form-control mr-10'}
                name={`${prefixName}[numAlumnos]`}
                type="number"
                required
                min={1}
                step={1}
                placeholder="# de Alumnos Becados"
                value={x.numAlumnos}
                onChange={e => handleInputChange(e, i, 'numAlumnos')}
              /></div>
            <div className={'form-group col-md-3'}>
              {
                i===0 ?
                  <label htmlFor="descuentoInscripcion">Porcentaje descuento Inscripción</label>
                  : null
              }
              <div className=" input-group mr-10 col-sm-12">
                <div className="input-group-addon">%</div>
                <input
                  className='form-control'
                  name={`${prefixName}[descuentoInscripcion]`}
                  type="number"
                  required
                  min={0}
                  step={0.1}
                  max={100}
                  placeholder="descuento Inscripción"
                  value={x.descuentoInscripcion}
                  onChange={e => handleInputChange(e, i, 'descuentoInscripcion')}
                />
              </div>
            </div>
            <div className="form-group col-md-3">
              {i === 0 ?
                <label htmlFor="descuentoColegiatura">Porcentaje descuento Colegiatura</label>
                : null
              }
              <div className="input-group mr-10 col-sm-12">
                <div className="input-group-addon">%</div>
                <input
                  className='form-control '
                  name={`${prefixName}[descuentoColegiatura]`}
                  type="number"
                  required
                  min={0}
                  step={0.1}
                  max={100}
                  placeholder="descuento Colegiatura"
                  value={x.descuentoColegiatura}
                  onChange={e => handleInputChange(e, i,'descuentoColegiatura')}
                />
              </div>
            </div>
            <div className="form-group col-md-3">
              <div className={ (i=== 0 ? 'mt-30 ' : '') + "input-group col-sm-12"}>
                {inputList.length !== 0 &&
                <button
                  className="btn btn-link"
                  onClick={() => handleRemoveClick(i)}
                >Eliminar</button>}
              </div>
            </div>
            { errorDesc ?
              <div className='col-md-12'>
              <span className="help-block">El número total de alumnos con descuento
              no puede ser mayor que el máximo de lugares autorizados ({maxLugaresAutorizados}) para esta carrera</span>
              </div>
              : null
            }
          </div>
        );
      })}
      <button
        onClick={handleAddClick}
        className={'btn btn-light'}
      >Agregar descuento</button>
    </div>
  );
}

export default RegistrarDescuentos;
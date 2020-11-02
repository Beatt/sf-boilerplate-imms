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

  // handle click event of the Remove button
  const handleRemoveClick = index => {
    const list = [...inputList];
    list.splice(index, 1);
    setInputList(list);
  };

  // handle click event of the Add button
  const handleAddClick = () => {
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
      props.onChange(props.indexMonto, list);
    }
  };

  return (
    <div className={'form-inline'}>
      {inputList.map((x, i) => {
        const descId = `${props.carrera.id}-${i}`;
        const prefixName = `${props.prefixName}[${i}]`
        return (
          <div className="row mb-5" key={i}>
              <div className={'form-group col-md-3'}>
                { i==0 ?
                  <label htmlFor="numAlumnos">Número de Alumnos</label>
                  : null
                }
                <input
                className={'form-control mr-10'}
                name={`${prefixName}[numAlumnos]`}
                type="number"
                min={1}
                step={1}
                placeholder="# de Alumnos Becados"
                value={x.numAlumnos}
                onChange={e => handleInputChange(e, i, 'numAlumnos')}
              /></div>
            <div className={'form-group col-md-3'}>
              {
                i==0 ?
                  <label htmlFor="descuentoInscripcion">Porcentaje descuento Inscripción</label>
                  : null
              }
              <div className=" input-group mr-10 col-sm-12">
                <div className="input-group-addon">%</div>
                <input
                  className='form-control'
                  name={`${prefixName}[descuentoInscripcion]`}
                  type="number"
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
              {i == 0 ?
                <label htmlFor="descuentoColegiatura">Porcentaje descuento Colegiatura</label>
                : null
              }
              <div className="input-group mr-10 col-sm-12">
                <div className="input-group-addon">%</div>
                <input
                  className='form-control '
                  name={`${prefixName}[descuentoColegiatura]`}
                  type="number"
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
              <div className={ (i== 0 ? 'mt-30 ' : '') + "input-group col-sm-12"}>
                {inputList.length !== 0 &&
                <button
                  className="btn btn-link"
                  onClick={() => handleRemoveClick(i)}
                >Eliminar</button>}
              </div>
            </div>
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
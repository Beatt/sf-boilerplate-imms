import * as React from 'react'
import './styles.scss'

const RegistrarDescuentos = (props) => {

  const [inputList, setInputList] = React.useState([]);

  // handle click event of the Remove button
  const handleRemoveClick = index => {
    const list = [...inputList];
    list.splice(index, 1);
    setInputList(list);
  };

  // handle click event of the Add button
  const handleAddClick = () => {
    setInputList([...inputList, { numAlumns: "", descIns: "", descCol: "" }]);
  };

  const handleInputChange = (e, index) => {
    const { name, value } = e.target;
    const list = [...inputList];
    list[index][name] = value;
    setInputList(list);
    console.log(e);
    console.log(inputList)
  };

  return (
    <div className={'row'}>
      {inputList.map((x, i) => {
        const descId = `${props.carrera.id}-${i}`;
        return (
          <div className="form-inline mb-5" key={i}>
            <div className=''>
              <input
                className={'form-control mr-10'}
                name={`numAlumns`}
                type="number"
                min={1}
                step={1}
                placeholder="# de Alumnos Becados"
                value={x.numAlumns}
                onChange={e => handleInputChange(e, i)}
              />
            <div className=" input-group mr-10 col-sm-3">
              <div className="input-group-addon">%</div>
              <input
                className='form-control'
                name={`descIns`}
                type="number"
                min={0}
                step={1}
                max={100}
                placeholder="descuento Inscripción"
                value={x.descIns}
                onChange={e => handleInputChange(e, i)}
              />
            </div>
            <div className="input-group mr-10 col-sm-3">
              <div className="input-group-addon">%</div>
              <input
                className='form-control '
                name={`descCol`}
                type="number"
                min={0}
                step={1}
                max={100}
                placeholder="descuento Colegiatura"
                value={x.descCol}
                onChange={e => handleInputChange(e, i)}
              />
            </div>
            <div className="input-group">
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
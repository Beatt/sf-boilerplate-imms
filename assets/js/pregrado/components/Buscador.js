import * as React from "react";

const Buscador = ({
                    handleSearch, handleExport, setSearch,
                  }) => {
  return (
    <div className='row'>
      <div className='col-md-3'>
        <button
          type='button'
          className='btn btn-success'
          onClick={handleExport}
        >
          Exportar CSV
        </button>
      </div>
      <div className='col-md-9 mb-15'>
        <div className='navbar-form navbar-right '>
          <div className="form-group">
            <input
              type="text"
              placeholder='Buscar por...'
              className='input-sm form-control'
              onChange={({target}) => setSearch(target.value)}
            />
          </div>
          <button
            type="button"
            className="btn btn-default"
            onClick={handleSearch}
          >
            Buscar
          </button>
        </div>
      </div>
    </div>
  );
}

export default Buscador
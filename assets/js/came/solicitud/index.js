import * as React from 'react'
import ReactDOM from 'react-dom'

const CameTableExample = (props) => {
    return (
      <table className="table">
          <thead>
          <tr>
              <td>Head1</td>
              <td>Head2</td>
              <td>Head3</td>
          </tr>
          </thead>
          <tbody>
          <tr>
              <td>Col1</td>
              <td>Col2</td>
              <td>Col3</td>
          </tr>
          </tbody>
      </table>
    );
}

export default {CameTableExample};

document.addEventListener('DOMContentLoaded', () => {
    ReactDOM.render(
        <CameTableExample
            solicitudes={window.SOLICITUDES}
        />,
        document.getElementById('solicitudes-table')
    )
})
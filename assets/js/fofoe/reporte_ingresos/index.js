import * as React from 'react'
import ReactDOM from 'react-dom'

const Index = () => {
  return (
    <div className="panel panel-default">

      <div className="panel-heading">
        Reporte de ingresos por concepto
      </div>
      <div className="panel-body">
        <a href="#" onClick="#">Download as CSV</a>

        <table className="table">
          <thead>
          <th>Mes/Año</th>
          <th>CC Área de la Salud</th>
          <th>Int. Méd</th>
          <th>Total Mensual</th>
          </thead>
          <tbody>
          <tr>
            <td>01/2020</td>
            <td>45,793,865</td>
            <td>0</td>
            <td>45,793,865</td>
          </tr>
          <tr>
            <td>02/2020</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>03/2020</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          </tbody>
          <tfoot>
          <tr>
            <td>Total por ciclo</td>
            <td>45,793,865</td>
            <td>0</td>
            <td>45,793,865</td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  );
};

      document.addEventListener('DOMContentLoaded', () => {
      ReactDOM.render(
        <Index/>,
        document.getElementById('reporte-wrapper')
      )
    })

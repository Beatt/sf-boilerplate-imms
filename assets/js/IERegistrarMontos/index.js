import React, { Component } from 'react';
import ReactDOM from 'react-dom'


const App = ({ Carrera }) => {
  return(
            <form class='form-inline'>
            {
                    Carrera.map((item) => {
                    return <div class='row'>
                            <div class='col-md-2'>
                                <label class='col-md-2'>Licenciatura</label>
                            </div>
                            <label class='col-md-2'>Trabajo Social</label>
                            <div class='col-md-2'>
                                <input type='text' name='inscripcion[]' class='form-control'/>    
                            </div>
                                 <div class='col-md-2'>
                                <input type='text' name='colegiatura[]' class='form-control'/>    
                            </div>
                        
                        </div>
                })}
                        
            </form>
        )
}

const Name = ({ Institution }) => {
  return(
    <p style={{ marginTop: '10px', fontSize: '16px', fontWeight: 'bold'}}>{Institution}</p>
  )
}

const Name2 = ({ Institution }) => {
  return(
    <p>La institución educativa <strong>{Institution}</strong>, confirma que el oficio adjunto, contiene el monto correspondiente a los montos de colegiatura e inscripción por cada una de las carreras mencionadas anteriormente</p>
  )
}




ReactDOM.render( <App Carrera={window.Carrera}/>,document.getElementById('pagos'));

ReactDOM.render( <Name Institution={window.name} />, document.getElementById('ie'));
ReactDOM.render( <Name2 Institution={window.name} />, document.getElementById('ie2'));


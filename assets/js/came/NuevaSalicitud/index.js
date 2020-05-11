import React from 'react'
import ReactDOM from 'react-dom'
//import './style.css'



class NuevaSolicitud extends React.Component {
    render(){
      return(
        <div>
          <hr/>
            <div>Nueva Solicitud</div>
          <hr/>
            <div>
                <p>Información Institución</p>
            </div>
            <div>
                Ingrese la Información correspondiente a la institución Educativa que solicita el campo
            </div>
            <div>
                <InfoInstitucion />
            </div>

            <div>
                <ConvenioVigente />
            </div>

            <div>
                <InfoVigente />
            </div>
        </div>
      )
    }
  }

const InfoInstitucion = () => {
    return(
        <table>
            <td>
                <tr>
                    <p>Nombre de la institución</p>
                </tr>
                <tr>
                   <select style={{
                            marginRight: '40px',
                            width: "500px"
                        }}>
                        <option value="0">--Seleccione un instituto--</option>
                        <option value="1">Instituto uno</option>
                        <option value="2">Instituto dos</option>
                        <option value="3">Instituto tres</option>
                    </select>
                </tr>
                <tr>
                    <p>RFC:</p>
                    <input type="text" />
                </tr>
                <tr>
                    <p>Número teléfonico</p>
                    <input type="text" />
                </tr>
                <tr>
                    <p>Correo electrónico</p>
                    <input type="text" />
                </tr>
            </td>
            <td>
                <tr>
                    
                </tr>
                <tr>
                    <p>Domicilio</p>
                    <input type="text" style={{
                        width: "400px"
                    }}/>
                </tr>
                <tr>
                    <p>Página web (Opcional)</p>
                    <input type="text" />
                </tr>
                <tr>
                    <p>Número de tax (Opcional)</p>
                    <input type="text" />
                    <input type="submit" value="Guardar Institución Educativa" 
                        style={{
                            marginTop: "30px"
                        }}
                    />
                </tr>
            </td>
        </table>
    )
}

const ConvenioVigente = () => {
    return(
        <div>
            <div>
            <table className="convenio">
                <thead>
                    <td>
                        <tr>
                            <p>Número</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Grad</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Ciclo</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Carrera</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Vigencia</p>
                        </tr>
                    </td>
                </thead>

                <tbody>
                    <td>
                        <tr>
                            <p>Número</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Grad</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Ciclo</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Carrera</p>
                        </tr>
                    </td>
                    <td>
                        <tr>
                            <p>Vigencia</p>
                        </tr>
                    </td>
                </tbody>
            </table>
            </div>

            <div className="status-color">
                <table>
                    <td>
                        <tr>
                            <div className="verde"></div>
                            <p className="leyenda-color">
                                Convenio está vigente, con fecha de término mayor a un año.
                            </p>
                        </tr>
                        <tr>
                            <div className="amarillo"></div>
                            <p className="leyenda-color">
                                Convenio vigente, con fecha de término
                                menor a un año pero mayor a 6 meses.
                                El convenio debería estar en trámites de renovación.
                            </p>
                        </tr>
                        <tr>
                            <div className="rojo"></div>
                            <p className="leyenda-color">
                                La vigencia del convenio está por terminar,
                                es necesario actualizar el convenio.
                            </p>
                        </tr>
                    </td>
            
                </table>
            </div>
        </div>
    )
}

const InfoVigente = () => {
    return(
        <div>
            <p style={{
                fontWeight: "bolder"
            }}>
                Información Solicitud Campos Clínicos
            </p>

            <p>
                Ingrese la información correspondiente a la carrera solicitada para campo clínico
            </p>

            <table>
                <td>
                    <tr>
                        <p>
                            Ciclo academicó
                        </p>
                        <select>
                            <option>CSS - Ciclo Clínico</option>
                        </select>
                    </tr>
                    <tr>
                        <p>
                            Periodo
                        </p>
                        <div>
                        Fecha Inicio: <input type="date" />
                        </div>
                        
                        <div>
                        Fecha Termino: <input type="date" />
                        </div>
                    </tr>
                    <tr>
                        <p>
                            Unidad Sede
                        </p>
                        <select  style={{
                            width: '500px'
                        }}>
                            <option>----------</option>
                        </select>
                    </tr>

                    <tr>
                        <p>
                            No. de lugares autorizados
                        </p>
                        <input type="text" />
                    </tr>
                   
                    <tr>
                        <input type="submit" value="Agregar Otro Campo Clínico"/>
                    </tr>
                    <tr>
                        <input type="submit" value="Terminar solicitud" 
                        style={{
                            width: '800px'
                        }}/>
                    </tr>
                </td>
                <td>
                    <tr>
                        <p>
                            Nivel
                        </p>
                        <select>
                            <option>Licenciatura</option>
                        </select>
                    </tr>

                    <tr>
                        <p>Horario del campo clínico (Opcional)</p>
                        <input type="text" />
                    </tr>
                    <tr >
                        <p style={{
                            marginTop: '80px'
                        }}>
                                No. de lugares solicitados
                        </p>
                        <input type="text" />
                     </tr>
                    <tr>
                        <input type="submit" value="Guardar Campo Clínico"/>
                    </tr>
                </td>
                <td>
                    <tr>
                        <p>
                            Carrera
                        </p>
                        <select>
                            <option>Trabajo Social</option>
                        </select>
                    </tr>
                    <tr>
                        <p>
                            Promoción de Inicio [sólo para internado]
                        </p>
                        <select>
                            <option>--</option>
                        </select>
                    </tr>

                </td>

            </table>

        </div>


    )
}

//ReactDOM.render(<NuevaSolicitud />, document.getElementById('root'))
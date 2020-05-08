import React from 'react'
import ReactDOM from 'react-dom'

class ValidaMontos extends React.Component {
    render(){
        return(
            <div>
                <div>
                    <hr/>
                    <p style={{
                        fontWeight: 'bolder'
                    }}>
                        Validación de Montos de Inscripción y Colegiatura
                    </p>
                    <hr />
                </div>
                <p style={{
                    fontWeight: 'bolder'
                }}>
                    Solicitud NS_006
                </p>

                <p style={{
                    fontWeight: 'bolder'
                }}>
                    Instituto Universitario de Ciencias Medicas y Humanisticas de Nayarit
                </p>

                <p>
                    Por favor valide los montos que se muestran a continuación con los reportados en el oficio
                </p>

                <p>
                    Oficio: <a href="#">OfficioMontos-NS_006.pdf</a>
                </p>
                <p>
                    Valide los montos correspondientes a cada carrera de su solicitud
                </p>
                <div>
                    <table>
                        <td>
                            <tr>
                            </tr>
                            <tr>
                                Licenciatura
                            </tr>
                            <tr>
                                Licenciatura
                            </tr>
                        </td>
                        <td>
                            <tr>
                                <p>
                                    Carrera
                                </p>
                            </tr>
                            <tr>
                                <p>
                                    Trabajo Social
                                </p>
                            </tr>
                            <tr>
                                <p>
                                    Trabajo Social
                                </p>
                            </tr>
                        </td>
                        <td>
                            <tr>
                                <p>
                                    Inscripción
                                </p>
                            </tr>
                            <tr>
                               <input type="text" placeholder="$$$$$"/>
                            </tr>
                            <tr>
                               <input type="text" placeholder="$$$$$"/>
                            </tr>
                        </td>
                        <td>
                            <tr>
                                <p>
                                   Colegiatura
                                </p>
                            </tr>

                            <tr>
                               <input type="text" placeholder="$$$$$"/>
                            </tr>
                            <tr>
                               <input type="text" placeholder="$$$$$"/>
                            </tr>
                        </td>

                       
                        
                    </table>

                    <p>
                            ¿Todos los montos corresponde?
                        </p>
                        <div>
                            <input type="checkbox" />si
                            <input type="checkbox" />no
                        </div>

                        <div>
                            <p>
                                Observaciones
                            </p>
                            <input type="text" 
                                style={{
                                    width: '800px'
                                }}/>
                        </div>

                        <div>
                            <input type="submit" value="Guardar" />
                        </div>
                </div>
            </div>
        )
    }
}



//ReactDOM.render(<ValidaMontos />, document.getElementById('root'))
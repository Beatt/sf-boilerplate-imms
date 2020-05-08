import * as React from 'react'
import { getActionNameByCampoClinico } from "../../utils";

const UploadFile = () => {
  const { useState } = React
  const [isLoading, setIsLoading] = useState(false)
  const [feedbackMessage, setFeedbackMessage] = useState('')

  return(
    <div style={{ position: 'relative' }}>
      <label htmlFor="">{!isLoading ?
        getActionNameByCampoClinico(campoClinico.estatus.nombre) :
        'Cargando....'
      }</label>
      <input
        type="file"
        onChange={({ target }) => handleUploadComprobantePago(campoClinico, target)}
      />
      {feedbackMessage && <span className='error-message'>{feedbackMessage}</span>}
    </div>
  )
}

export default UploadFile

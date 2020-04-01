import * as React from 'react'
import ReactDOM from 'react-dom'
import './styles.scss'

const App = ({ convenios }) => {
  return(
    convenios.map(item =>
      <h2 className='link'>{item.name}</h2>
    )
  )
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <App
      convenios={window.CONVENIOS_PROPS}
    />,
    document.getElementById('convenios')
  )
})

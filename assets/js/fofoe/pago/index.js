import * as React from 'react'
import ReactDOM from 'react-dom'

const PagoIndex = (props) => {
    return (<>
        <h2>Area de pagos en construcción</h2>
    </>);
}

document.addEventListener('DOMContentLoaded', () => {
    const indexDom = document.getElementById('fofoe-wrapper-index');
    if (indexDom) {
        ReactDOM.render(
            <PagoIndex
                pagos={window.PAGOS}
                meta={window.META}
            />, indexDom
        )
    }
})
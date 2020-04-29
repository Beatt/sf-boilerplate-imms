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

class ExampleForm extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            fecha: '',
        };
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();
        let data = new FormData();
        data.append('token', this.props.token);
        fetch(this.props.action, {
            method: this.props.method,
            body: data,
        }).then(res => res.json()).then(json =>{
            alert("Response: " + json.message);
        }).catch(error => alert('Error server: ' + error));
    }

    render() {
        return (
            <>
                <hr/>
                <form onSubmit={this.handleSubmit} >
                    <div className="form-group row">
                        <label htmlFor="text" className="col-4 col-form-label">Fecha Text Field</label>
                        <div className="col-8">
                            <div className="input-group">
                                <div className="input-group-prepend">
                                    <div className="input-group-text">
                                        <i className="fa fa-address-card"></i>
                                    </div>
                                </div>
                                <input id="text"  type="date" className="form-control" value={this.state.value} />
                            </div>
                        </div>
                    </div>
                    <p>{this.props.token}</p>
                    <div className="form-group row">
                        <div className="offset-4 col-8">
                            <button name="submit" type="submit" className="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </>
        )
    }
}

export default {CameTableExample, ExampleForm};

document.addEventListener('DOMContentLoaded', () => {
    const indexDom = document.getElementById('solicitudes-table');
    const createDom = document.getElementById('solicitud-wrapper');
    if(indexDom) {
        ReactDOM.render(
            <CameTableExample
                solicitudes={window.SOLICITUDES}
            />,indexDom
        )
    }
    if(createDom) {
        ReactDOM.render(
            <ExampleForm
                action='/api/solicitud'
                method={'post'}
                token={window.CSRF_TOKEN} />, createDom
        )
    }
})
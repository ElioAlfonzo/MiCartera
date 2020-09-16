import React, { Component } from "react";
import ReactDOM from "react-dom";
import TransferForm from './TransferForm';
import TransferList from './TransferList';
import url from '../url';

export default class Example extends Component {
    constructor(props){
        super(props)

        this.state = {
            money: 0.0,
            transfers : [],
            error : null,
            form: {
                description:'',
                amount: '',
                wallet_id: 1
            }
        }

        this.handleChange = this.handleChange.bind(this) //para que es esto el bind?
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    async handleSubmit(e){
        e.preventDefault(); //para que no serecarge la pag

        try {
            let config = {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify(this.state.form)
            }

            let res = await fetch(`${url}/api/transfer`, config)
            let data = await res.json()

            //actualizamos estado
            this.setState({
                transfers: this.state.transfers.concat(data),
                money: this.state.money + (parseInt(data.amount))
            })

        } catch (error) {
            this.setState({
                error
            })
        }
        
    }

    handleChange(e){ //donde va tomando los valores ingresando
        this.setState({
            form: {
                ...this.state.form,
                [e.target.name] : e.target.value
            }
        })
        console.log(e.target.value);
    }

    async componentDidMount(){
        try {
            let res = await fetch(`${url}/api/wallet`)
            let data = await res.json()
            // actualizamos el estado
            this.setState({
                money: data.money,
                transfers: data.transfers
            })

        } catch (error) {
            //en caso de un error
            this.setState({
                error
            })
        }
    }
    
    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-12-m-t-md">
                        <p className="title"> $ {this.state.money} </p>
                    </div>

                    <div className="col-md-12">
                        <TransferForm
                            form = {this.state.form}
                            onChange={this.handleChange}
                            onSubmit={this.handleSubmit}
                            
                        />
                    </div>
                    
                </div>

                <div className="m-t-md">
                    <TransferList
                        transfers = {this.state.transfers}
                    />
                </div>
            </div>
        );
    }
}

if (document.getElementById("example")) {
    ReactDOM.render(<Example/>, document.getElementById("example"));
}

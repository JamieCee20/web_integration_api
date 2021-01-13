import React from 'react';
import Login from './Login.js';
import Update from './Update.js';

class Admin extends React.Component {
    constructor(props) {
        super(props);
        this.state = {"admin":false, "email":"", "password":"", adminStatus: ""}

        this.handlePassword = this.handlePassword.bind(this);
        this.handleEmail = this.handleEmail.bind(this);
    }

    componentDidMount() {
        if (localStorage.getItem('myToken')) {
            this.setState({ "admin": true });
        }
    }

    postData = (url, myJSON, callback) => {
        fetch(url, {
                method: 'POST',
                headers: new Headers(),
                body: JSON.stringify(myJSON)
            })
            .then((response) => response.json())
            .then((data) => {
                callback(data)
            })
            .catch((err) => {
                console.log("something went wrong ", err)
            });
    }

    loginCallback = (data) => {
        console.log(data)
        if (data.status === 200) {
            this.setState({ "admin": true, "adminStatus": data.adminStatus })
            localStorage.setItem('myToken', data.token)
        }
    }

    updateCallback = (data) => {
        console.log(data)
        if(data.status !== 200) {
            this.setState({"admin":false, "adminStatus": ""})
            localStorage.removeItem('myToken')
        }
    }

    handleLoginClick = () => {
        const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/login"
        let myJSON = { "email": this.state.email, "password": this.state.password }
        this.postData(url, myJSON, this.loginCallback)
    }

    handleUpdateClick = (sessionId, title) => {
        const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/update"

        if (localStorage.getItem('myToken')) {
            let myToken = localStorage.getItem('myToken')
            let myJSON = {
                "token": myToken,
                "title": title,
                "sessionId": sessionId
            }
            this.postData(url, myJSON, this.updateCallback)
        } else {
            this.setState({ "admin": false })
        }

    }

    handleLogoutClick = () => {
        this.setState({ "admin": false })
        localStorage.removeItem('myToken')
    }

    handlePassword = (e) => {
        this.setState({password:e.target.value})
    }

    handleEmail = (e) => {
        this.setState({email:e.target.value})
    }

    render() {
        let page = <Login handleLoginClick={this.handleLoginClick} email={this.state.email} password={this.props.password} handleEmail={this.handleEmail} handlePassword={this.handlePassword} />
        if (this.state.admin) {
			page = 
				<div >
                    <button className="AdminButton" onClick = { this.handleLogoutClick } > Logout </button>
                	<Update handleUpdateClick={this.handleUpdateClick} adminStatus={this.state.adminStatus} />
				</div>
        }

        return ( 
            <div>
                <h1> Admin </h1>
                {page}
            </div>
        );
    }
}

export default Admin;
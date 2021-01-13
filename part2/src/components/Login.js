import React from 'react';

class Login extends React.Component {
    render() {
        return (
            <div className="LoginForm">
                <input
                type='text'
                placeholder='email'
                value={this.props.email}
                onChange={this.props.handleEmail}
                />
                <input
                type='password'
                placeholder='password'
                value={this.props.password}
                onChange={this.props.handlePassword}
                />
                <button className="AdminButton" onClick={this.props.handleLoginClick}>Login</button>
            </div>
        );
    }
}

export default Login;
import React from 'react';
import UpdateItem from './UpdateItem.js';

class Update extends React.Component {
    state = {data: []}

    componentDidMount() {
        const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/sessions"
        fetch(url)
        .then( (response) => response.json())
        .then( (data) => {
            this.setState({data: data.data})
        })
        .catch( (err) => {
            console.log("Something went wrong ", err)
        });
    }

    render() {
        return (
            <div>
                {this.state.data.map((details, i) => (<UpdateItem key={i} details={details} handleUpdateClick={this.props.handleUpdateClick} adminStatus={this.props.adminStatus} />))}
            </div>
        );
    }
}

export default Update;
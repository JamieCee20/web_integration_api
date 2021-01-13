import React from 'react';

class Chair extends React.Component {

    componentDidMount() {
        console.log("Tits");
    }



    render() {

        return (
            <p>Session Chair: <span>{this.props.details.name}</span></p>
        );
    }
}

export default Chair;
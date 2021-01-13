import React from 'react';

class UpdateItem extends React.Component {

    state = {title: this.props.details.name}

    handleTitleChange = (e) => {
        this.setState({title: e.target.value })
    }

    handleTitleUpdate = () => {
        if(this.props.adminStatus === "1") {
            this.props.handleUpdateClick(this.props.details.sessionId, this.state.title)
        }
    }

    render() {
        return (
            <div>
                <textarea rows="4" cols="50" value={this.state.title} onChange={this.handleTitleChange} />
                <button className="AdminButton" onClick={this.handleTitleUpdate} disabled={this.props.adminStatus !== "1"}>Update</button>
            </div>
        );
    }
}

export default UpdateItem;
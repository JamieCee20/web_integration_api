import React from 'react';

class SelectDay extends React.Component {
    render() {
      return (
        <label>
          Select Day:
          <select value={this.props.day} onChange={this.props.handleDaySelect}>
            <option value="">Any</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
          </select>
        </label>
      )
    }
  }

  export default SelectDay;
import React from 'react';
import Content from './Content.js';
import Chair from './Chair.js';

class Schedule extends React.Component {

  state = { display: false, displayMore: false, data: [], chair: [] }

  loadContentDetails = () => {
    const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/content?sessionId=" + this.props.details.sessionId
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        this.setState({ data: data.data })
      })
      .catch((err) => {
        console.log("something went wrong ", err)
      }
    );
  }

  loadSessionChair = () => {
    const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/sessionChair?chairId=" + this.props.details.chairId
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
		this.setState({ chair: data.data })
		console.log(data.data);
      })
      .catch((err) => {
        console.log("something went wrong ", err)
      }
    );
  }

  handleContentClick = (e) => {
    this.setState({ displayMore: !this.state.displayMore })
    this.loadContentDetails()
  }

  handleSlotClick = () => {
    this.setState({ display: !this.state.display })
  }

  render() {

    /**
     * Defined variables to be used in various 
     */
    let content = "";
    let slots = "";
    let hours = "";
    let minutes = "";
    let endhours = "";
    let endminutes = "";
	let sessionChair = "";

    /**
     * Displays single digit values as double. I.e 9:30 will become 09:30 or 12:0 will become 12:00
     */
    if (this.props.details.startHour.length === 1) {
      hours = <span>0{this.props.details.startHour}</span>
    } else {
      hours = <span>{this.props.details.startHour}</span>
    }
    if (this.props.details.startMinute.length === 1) {
      minutes = <span>{this.props.details.startMinute}0</span>
    } else {
      minutes = <span>{this.props.details.startMinute}</span>
    }
    if (this.props.details.endHour.length === 1) {
      endhours = <span>0{this.props.details.endHour}</span>
    } else {
      endhours = <span>{this.props.details.endHour}</span>
    }
    if (this.props.details.endMinute.length === 1) {
      endminutes = <span>{this.props.details.endMinute}0</span>
    } else {
      endminutes = <span>{this.props.details.endMinute}</span>
    }


    /**
     * Displays content information when required
     */
    if (this.state.displayMore) {
		content = this.state.data.map((details, i) => 
			(
				<Content key={i} details={details} />
			)
      );
	}
	
	// if (this.state.chair && this.state.chair.length > 0) {
		sessionChair = this.state.chair.map((details, i) => 
			(
				<Chair key={i} details={details} />
			)
		);
	// }

    /**
     * Displayed session type and duration when required
     */
    if (this.state.display) {
      slots = <div>
        <p onClick={this.handleContentClick}>Session Type: {this.props.details.sessionType} <br />
        Duration: {hours}:{minutes} - {endhours}:{endminutes} <br />
        Room: {this.props.details.room} <br />
		Test: {this.props.details.chairId} <br />
        {sessionChair} <br />
        </p>
        {content}
      </div>
    }

    return (
      <div className="ScheduleContent">
        <h2 onClick={this.handleSlotClick}>{this.props.details.name} - {this.props.details.dayString}</h2>
        {slots}
      </div>
    );
  }
}


export default Schedule;